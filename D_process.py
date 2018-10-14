#!/usr/bin/env python
# coding: utf-8


'''
author:SZTU_1013 - Lub
'''

#version 1.0

#######################################################
'''
function:通过人体传感器检测是否有人经过，如果有人则报警并向微信发送信息。可用网页端控制布防与撤防。
'''



import RPi.GPIO as G
import threading
import time
import httplib
import urllib


def Wechat_push(sendkey,text,desp,model='0'):
    '''By SZTU_Re
        coding=utf-8
        sendkey 从server酱那里获取的
        text 消息标题
        desp 消息长内容
        model 选择推送模式默认为一对多推送模式，若模式为微信推送请设置为不为0的任意字符
        '''
    action_time = time.time()
    local_time = time.strftime('%Y-%m-%d %H:%M:%S',time.localtime(time.time()))
    new_desp = urllib.quote(local_time + desp)
    if model == '0':
        url = "https://pushbear.ftqq.com/sub?sendkey="+sendkey+"&text="+text+"&desp="+new_desp
        conn = httplib.HTTPConnection("pushbear.ftqq.com")
        conn.request(method="GET",url=url)
        response = conn.getresponse()
        res = response.read()
        return action_time,res
    else:
        url = "https://sc.ftqq.com/"+sendkey+".send?text="+text+"&desp="+new_desp
        conn = httplib.HTTPConnection("sc.ftqq.com")
        conn.request(method="GET",url=url)
        response = conn.getresponse()
        res = response.read()
        return action_time,res
    #实例①-一对多推送
    #Wechat_push('5689-eb08971edd400b317404935ace97e50b','test','test')



class Job(threading.Thread):

    def __init__(self, *args, **kwargs):
        super(Job, self).__init__(*args, **kwargs)
        self.__flag = threading.Event()     # 用于暂停线程的标识
        self.__flag.set()       # 设置为True
        self.__running = threading.Event()      # 用于停止线程的标识
        self.__running.set()      # 将running设置为True

    def run(self):
        action_time = None
        while self.__running.isSet():
            self.__flag.wait()      # 为True时立即返回, 为False时阻塞直到内部的标识位为True后返回

            anybody = G.input(40)
            if anybody == 1:           #红外人体传感器检测到有人则输入高电平
                G.output(38,G.HIGH)
                print("好像有人进来了")
                if action_time == None :
                    action = Wechat_push('5668-570752d4e422a752d0187a804faf3a3b','有动静','好像有人进来了')
                    action_time = action[0]
                    check_wechat = action[1]
                    print('第一次发送' + check_wechat)  #打印发送结果
                elif action_time != None :
                    local_time = time.time()
                    check_time = local_time - action_time
                    if check_time >= 300:          #判断距上一次发送微信通知的时间，大于5分钟则再发送。
                        action = Wechat_push('5668-570752d4e422a752d0187a804faf3a3b','有动静','好像有人进来了')
                        action_time = action[0]
                    	check_wechat = action[1]
                        print('不是第一次' + check_wechat)  #打印发送结果
                    
                else:
                    print('错误')
            else:
                print("因该没人")
                G.output(38,G.LOW)
            time.sleep(1)

    def pause(self):
        self.__flag.clear()     # 设置为False, 让线程阻塞

    def resume(self):
        self.__flag.set()    # 设置为True, 让线程停止阻塞

    def stop(self):
        self.__flag.set()       # 将线程从暂停状态恢复, 如何已经暂停的话
        self.__running.clear()        # 设置为False