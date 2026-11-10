"""Integration checks: run with python tests/verify.py from the project root."""
import hashlib
import json
from html.parser import HTMLParser
from pathlib import Path
import shutil
import socket
import subprocess
import time
import urllib.error
import urllib.request
import urllib.parse
import zipfile

root = Path(__file__).resolve().parents[1]
php = shutil.which('php') or r'C:\xampp\php\php.exe'
files = json.loads(subprocess.check_output(
    [php, '-r', "require 'api/catalog.php'; echo json_encode(catalog());"], cwd=root
))
with socket.socket() as sock:
    sock.bind(('127.0.0.1', 0))
    port = sock.getsockname()[1]
server = subprocess.Popen([php, '-S', f'127.0.0.1:{port}', '-t', str(root)], stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
base = f'http://127.0.0.1:{port}'

def request(path, method='GET', expected=200):
    try:
        response = urllib.request.urlopen(urllib.request.Request(base + path, method=method))
    except urllib.error.HTTPError as error:
        response = error
    assert response.status == expected, (path, response.status)
    return response.headers, response.read()

try:
    for attempt in range(50):
        try:
            headers, html = request('/')
            break
        except urllib.error.URLError:
            time.sleep(.1)
    else:
        raise RuntimeError('PHP server did not start')
    class DownloadLinks(HTMLParser):
        def __init__(self):
            super().__init__()
            self.links = []
        def handle_starttag(self, tag, attrs):
            attrs = dict(attrs)
            if tag == 'a' and 'download' in attrs:
                self.links.append(attrs)
    page = DownloadLinks()
    page.feed(html.decode())
    assert len(page.links) == 1
    selected = files['exam-ready-systems']['file']
    assert page.links[0]['download'] == selected
    assert page.links[0]['href'] == './downloads/' + urllib.parse.quote(selected, safe='')
    assert b'>Download</a>' in html and b'id="downloads"' not in html
    for file_id, item in files.items():
        filename = item['file']
        archive = root / 'downloads' / filename
        with zipfile.ZipFile(archive) as bundle:
            assert bundle.testzip() is None
            assert bundle.namelist(), ('Empty archive', filename)
        for path in [f'/downloads/{urllib.parse.quote(filename)}', f'/download.php?file={file_id}', f'/api/download.php?file={file_id}']:
            headers, data = request(path)
            assert hashlib.sha256(data).digest() == hashlib.sha256(archive.read_bytes()).digest()
            assert headers.get_content_type() == 'application/zip'
            if 'download.php' in path:
                assert headers['Content-Disposition'] == f'attachment; filename="{filename}"'
                assert int(headers['Content-Length']) == len(data)
        headers, data = request(f'/download.php?file={file_id}', method='HEAD')
        assert not data and int(headers['Content-Length']) == archive.stat().st_size
    for query in ['', '?file=unknown', '?file=../api/catalog.php', '?file[]=starter', '?file=purple-starter.zip', '?file=%00starter']:
        request('/download.php' + query, expected=404)
    request('/download.php?file=starter', method='POST', expected=405)
    # A missing selected archive must remove the button and return 404.
    archive = root / 'downloads' / selected
    hidden = archive.with_suffix('.test-hidden')
    archive.rename(hidden)
    try:
        request('/download.php?file=exam-ready-systems', expected=404)
        _, html = request('/')
        assert b'navbar-download' not in html
    finally:
        hidden.rename(archive)
    print('PASS: single navbar download link, ZIP integrity, byte-identical static/handler downloads, headers, HEAD, invalid IDs, traversal, array input, POST, missing files.')
finally:
    server.terminate()
    server.wait(timeout=5)
