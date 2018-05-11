<?php

namespace app\socketio\controller;
use Workerman\Worker;
use PHPSocketIO\SocketIO;
class Sendmsg
{
	
	
   public function index(){
       if(!IS_CLI){
            die("access illegal");
        }
	   	require_once VENDOR_PATH.'socket/autoload.php';
		// 全局数组保存uid在线数据
		$uidConnectionMap = array();
		// 记录最后一次广播的在线用户数
		$last_online_count = 0;
		// 记录最后一次广播的在线页面数
		$last_online_page_count = 0;        
		//$web = new WebServer();   
		$io = new SocketIO(3120);
		// 当有客户端连接时
		// 当有客户端连接时
		$io->on('connection', function($socket)use($io){
			
			$socket->on('login', function ($uid)use($socket){
		        global $uidConnectionMap, $last_online_count, $last_online_page_count;
		        // 已经登录过了
		        if(isset($socket->uid)){
		            return;
		        }
		        // 更新对应uid的在线数据
		        $uid = (string)$uid;
		        if(!isset($uidConnectionMap[$uid]))
		        {
		            $uidConnectionMap[$uid] = 0;
		        }
		        // 这个uid有++$uidConnectionMap[$uid]个socket连接
		        ++$uidConnectionMap[$uid];
		        // 将这个连接加入到uid分组，方便针对uid推送数据
		        $socket->join($uid);
		        $socket->uid = $uid;
		        // 更新这个socket对应页面的在线数据
		       // $socket->emit('online_box', "当前<b>{$last_online_count}</b>人在线，共打开<b>{$last_online_page_count}</b>个页面");
		    });														
			  // 定义chat message事件回调函数
			 
			  $socket->on('chat message', function($msg)use($io){
			    // 触发所有客户端定义的chat message from server事件
			    $io->emit('chat message from server', $msg);
			  });
		});

		// 监听一个http端口，通过http协议访问这个端口可以向所有客户端推送数据(url类似http://ip:9191?msg=xxxx)
		$io->on('workerStart', function()use($io) {
		    $inner_http_worker = new Worker('http://a.com:9191');
		    $inner_http_worker->onMessage = function($http_connection, $data)use($io){
		    	 $_POST = $_POST ? $_POST : $_GET;
		    	  $to = @$_POST['to'];
		        if(!isset($_POST['msg'])) {
		            return $http_connection->send('fail, $_POST["msg"] not found');
		        }
		        if($to){
			        $io->to($to)->emit('chat message from server', $_POST['msg']);		        	
		        }else{
			        $io->emit('chat message from server', $_POST['msg']);		        	
		        }

		        $http_connection->send($_POST['msg']);
		    };
		    $inner_http_worker->listen();
		});
		
		
					
			
		Worker::runAll();	
	}
  
}