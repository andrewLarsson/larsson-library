"""Exercise all five API resources against an isolated migration/test instance.

Usage: python3 tests/integration.py http://127.0.0.1:8080 /path/to/basic-auth.json
The JSON file contains username/password and must never be committed. Production
data must not be the target: these tests create/update/delete fixture records.
"""
import base64
import json
import secrets
import sys
import urllib.request

base_url = sys.argv[1].rstrip('/')
credentials = json.load(open(sys.argv[2]))
token = base64.b64encode((credentials['username']+':'+credentials['password']).encode()).decode()

def request(resource, method='GET', payload=None, identity=None):
    url = base_url+'/api/'+resource+'/'
    if identity:
        url += '?'+identity[0]+'='+str(identity[1])
    data = None if payload is None else json.dumps(payload).encode()
    req = urllib.request.Request(url, data=data, method=method,
        headers={'Host':'library.andrewlarsson.com','Authorization':'Basic '+token,'Content-Type':'application/json'})
    with urllib.request.urlopen(req, timeout=20) as response:
        assert response.status == 200, (resource,method,response.status)
        return json.load(response)

before = {name:request(name) for name in ['author','bookseries','book','user','checkout']}
suffix = secrets.token_hex(8)
author = request('author','POST',{'DisplayName':'Migration '+suffix,'FirstName':'Migration','LastName':suffix})
series = request('bookseries','POST',{'Title':'Migration '+suffix})
user = request('user','POST',{'DisplayName':'Migration '+suffix,'FirstName':'Migration','LastName':suffix})
book_payload = {'ISBN13':str(secrets.randbelow(10**13)).zfill(13),'AuthorID':author['AuthorID'],'BookSeriesID':series['BookSeriesID'],'Title':'Migration caf\u00e9 '+suffix,'Synopsis':'Temporary validation record'}
book = request('book','POST',book_payload)
assert book['Title'] == book_payload['Title'], 'Latin1 storage/UTF8 JSON round trip failed'
checkout = request('checkout','POST',{'BookID':book['BookID'],'UserID':user['UserID']})
records = [('author',author,'AuthorID','DisplayName'),('bookseries',series,'BookSeriesID','Title'),('book',book,'BookID','Synopsis'),('user',user,'UserID','DisplayName'),('checkout',checkout,'CheckoutID','InDate')]
for name,record,key,field in records:
    assert request(name,identity=(key,record[key]))[key] == record[key]
    record[field] = '2026-10-21 12:00:00' if name=='checkout' else 'Updated '+suffix
    updated = request(name,'PUT',record)
    assert updated[field] == record[field], name+' update failed'
for name,record,key in [('checkout',checkout,'CheckoutID'),('book',book,'BookID'),('bookseries',series,'BookSeriesID'),('author',author,'AuthorID'),('user',user,'UserID')]:
    request(name,'DELETE',identity=(key,record[key]))
after = {name:request(name) for name in before}
assert before == after, 'Fixture cleanup did not restore the original records'
print(json.dumps({'result':'PASS','resources':list(before),'checks':['list','create','read','update','delete','related checkout','UTF8 JSON / latin1 storage','original records unchanged'],'baseline_counts':{k:len(v) for k,v in before.items()}}))
