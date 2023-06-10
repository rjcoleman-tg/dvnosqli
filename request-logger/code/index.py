import os, sys
from http.server import HTTPServer 
from http.server import SimpleHTTPRequestHandler
from datetime import datetime

class HandlerClass(SimpleHTTPRequestHandler):
  
  requested_path = "/"
  do_not_log = ["/","/favicon.ico","/log.txt", "/empty.html"]

  # respond to all requests with log.txt
  def do_GET(self):
    self.requested_path = self.path
    if self.path not in self.do_not_log:
      print('Access request from ' + self.address_string() + ': ' + self.path)
      self.path = "empty.html";
    else:
      self.path = "log.txt";

    return SimpleHTTPRequestHandler.do_GET(self)

  def log_message(self, format, *args):
    if self.requested_path not in self.do_not_log:
      time_now = datetime.now()
      ts = time_now.strftime('%Y-%m-%d %H:%M:%S')
      msg = '(' + ts + ') Access request from ' + self.address_string() + ': ' + self.requested_path + "\n"
      file=open("log.txt", "a")
      file.write(msg)
      file.close()

if __name__ == '__main__':
  try:
    os.system('touch /code/log.txt')
    os.system('truncate -s 0 /code/log.txt')
    protocol = "HTTP/1.0"
    addr = len(sys.argv) < 2 and "0.0.0.0" or sys.argv[1]
    port = len(sys.argv) < 3 and 8090 or int(sys.argv[2])
    HandlerClass.protocol_version = protocol
    httpd = HTTPServer((addr, port), HandlerClass)
    sa = httpd.socket.getsockname()
    print("Serving HTTP on", sa[0], "port", sa[1], "...")
    httpd.serve_forever()
  except Exception as e: 
    print(e)
    exit()
