#!/usr/bin/python
#coding: utf8


'''
author:SZTU_1013 - Lub
'''

#version 1.0

#######################################################
'''
function:通过人体传感器检测是否有人经过，如果有人则报警并向微信发送信息。可用网页端控制布防与撤防。
'''



import sys
import time
#龙卷风框架模块
import tornado.ioloop
import tornado.web
import tornado.httpserver
import tornado.options
from tornado.options import define,options
#引用线程脚本
from D_process import *
#引入GPIO控制模块
import RPi.GPIO as G
define("port",default=8888,type=int)

G.setwarnings(False)    #关闭GPIO警告提示
G.setmode(G.BOARD)     #设置IO口编码为BOARD
 #初始化IO口
G.setup(38,G.OUT,initial = G.LOW)
G.setup(40,G.IN,pull_up_down=G.PUD_DOWN)

#设置线程
state = Job()

#定义确认变量，便于向php发送当前状态
global current_state
current_state = {"state":0}


class IndexHandler(tornado.web.RequestHandler):
        def get(self):
        	#收到确认状态请求后回应当前状态
                global current_state
                ask_state = self.get_argument('ask_state')
                if ask_state == "1":
                        self.write(current_state)
                else :
                        self.write(bytes("error"))
                
        def post(self):
        	#用户请求通过POST传递，根据用户请求进行布撤防。同时改变当前状态的变量值以便向php发送当前状态
                global current_state
                control = self.get_argument('control')
                if(control == 'Setup'):
                        if not state.is_alive():
                                state.start()
                                self.write("1")
                                current_state = {"state":1}
                                print("设防成功")
                        else:
                                state.resume()
                                self.write("1")
                                current_state = {"state":1}
                                print("设防成功")
                elif(control == 'Withdraw'):
                        state.pause()
                        G.output(38,G.LOW)
                        current_state = {"state":0}
                        self.write("1")
                        print("撤防成功")
                else:
                        return False
#判断是否本模块自身运行，开启服务
if __name__ == '__main__':
        tornado.options.parse_command_line()
        app = tornado.web.Application(handlers=[(r"/",IndexHandler)])
        http_server = tornado.httpserver.HTTPServer(app)
        http_server.listen(options.port)
        tornado.ioloop.IOLoop.instance().start()