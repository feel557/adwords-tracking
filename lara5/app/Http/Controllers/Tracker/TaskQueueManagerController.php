<?php namespace App\Http\Controllers\Tracker;
use App\Http\Controllers\BaseController;
use App\Libraries\IPLocation\IPLocation;
use View;
use Input;
use Redirect;
use DB;
use Auth;
use Excel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

use App\Http\Controllers\Adwords\InternalAdwordsController;

class TaskQueueManagerController extends BaseController {



function getTest(){

//$this->blockIPAdwords(Input::get('json'));
	//$this->rabbitMQSend( "blockip", json_encode(array()) );
	//$taskQueueManager = new TaskQueueManagerController();
//$taskQueueManager->rabbitMQSend( "blockip", json_encode(array("task_id" => $task_id,"ip" => $ipAddress, "tracker_id" => $arrayTracker[0]->id)) );
//$taskQueueManager->rabbitMQSend( "blockip", json_encode(array()) );

}

/* RabbitMQ Workers */

// #url /mq/mq-worker-loc/
function getMqWorkerLoc(){

$this->rabbitMQGet("iplocation", "ipLocationGet");

}

// #url /mq/mq-worker-block/
function getMqWorkerBlock(){

$this->rabbitMQGet("blockip", "blockIPAdwords");

}


/* RabbitMQ Basic */

public function rabbitMQSend($queueName, $message){

$connection = new AMQPStreamConnection(
            'localhost',	#host - имя хоста, на котором запущен сервер RabbitMQ
            5672,       	#port - номер порта сервиса, по умолчанию - 5672
            'guest',    	#user - имя пользователя для соединения с сервером
            'guest'     	#password
            );


        /** @var $channel AMQPChannel */
        $channel = $connection->channel();

        $channel->queue_declare(
            $queueName,	#queue name - Имя очереди может содержать до 255 байт UTF-8 символов
            false,      	#passive - может использоваться для проверки того, инициирован ли обмен, без того, чтобы изменять состояние сервера
            false,      	#durable - убедимся, что RabbitMQ никогда не потеряет очередь при падении - очередь переживёт перезагрузку брокера
            false,      	#exclusive - используется только одним соединением, и очередь будет удалена при закрытии соединения
            false       	#autodelete - очередь удаляется, когда отписывается последний подписчик
            );

        $msg = new AMQPMessage($message);

        $channel->basic_publish(
            $msg,       	#message
            '',         	#exchange
            $queueName	 	#routing key
            );

        $channel->close();
        $connection->close();

}



public function rabbitMQGet($queueName, $funcName){


$connection = new AMQPStreamConnection(
            'localhost',	#host
            5672,       	#port
            'guest',    	#user
            'guest'     	#password
            );

        $channel = $connection->channel();

        $channel->queue_declare(
            $queueName,	#имя очереди, такое же, как и у отправителя
            false,      	#пассивный
            false,      	#надёжный
            false,      	#эксклюзивный
            false       	#автоудаление
            );

        $channel->basic_consume(
            $queueName,                	#очередь
            '',                         	#тег получателя - Идентификатор получателя, валидный в пределах текущего канала. Просто строка
            false,                      	#не локальный - TRUE: сервер не будет отправлять сообщения соединениям, которые сам опубликовал
            true,                       	#без подтверждения - отправлять соответствующее подтверждение обработчику, как только задача будет выполнена
            false,                      	#эксклюзивная - к очереди можно получить доступ только в рамках текущего соединения
            false,                      	#не ждать - TRUE: сервер не будет отвечать методу. Клиент не должен ждать ответа
            array($this, $funcName)	#функция обратного вызова - метод, который будет принимать сообщение
            );

        while(count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();

}



/* Logic Functions */

// Adwords Block IP
public function blockIPAdwords($msg){

$internalAdwordsController = new InternalAdwordsController();

//$json = json_decode($msg->body);
$json = json_decode($msg);
// Save to db
$ipAddress = $json->ip;
$task_id = $json->task_id;
$tracker_id = $json->tracker_id;

DB::table('_trackers_tasks_current')
->where("id", "=", $task_id)
->update(array(
'updated_at' => time() )
);

//get campaign ip from db

$array2 = DB::table('_trackers')
->where('id', '=', $tracker_id)
->take(1)
->get();

if( isset($array2[0]) && count($array2)>0 ){

if($array2[0]->tracking_level == 2){
$campaignId = $array2[0]->tracking_item;
}elseif($array2[0]->tracking_level == 1){
$campaignId = "account";
}

// then block ip
$blockFunc = $internalAdwordsController->blockIPAdwordsFunction($campaignId, $ipAddress, $array2[0]->user);
if($blockFunc == 1){

//------------------------------------------------------
// statistic
$arrayStatistic = DB::table('_trackers_data_statistic')
->where('tracker_id', '=', $tracker_id)
->get();

$ip_blocked = $arrayStatistic[0]->ip_blocked + 1;

DB::table('_trackers_data_statistic')
->where('tracker_id', '=', $tracker_id)
->update(array(
'ip_blocked' => $ip_blocked
)
);

// blocked ip
$arrayBlockedIps = DB::table('_trackers_data_blocked_ip')
->where('tracker_id', '=', $tracker_id)
->where('ip', '=', $ipAddress)
->get();

if(count($arrayBlockedIps)<1){
DB::table('_trackers_data_blocked_ip')->insert(array(

'tracker_id' => $tracker_id,
'ip' => $ipAddress

)
);

}
//-------------------------------------------------

DB::table('_trackers_tasks_current')
->where("id", "=", $task_id)
->update(array(
'act' => 1 )
);


}else{


DB::table('_trackers_tasks_current')
->where("id", "=", $task_id)
->update(array(
'errors' => $blockFunc )
);


}
}


}




// Get IP Location

public function ipLocationGet($msg){

$json = json_decode($msg);
//$json = json_decode($msg->body);
// Save to db
$ip = $json->ip;
$task_id = $json->task_id;
$tracker_data_id = $json->tracker_data_id;


//Load the class
$ipLite = new IPLocation;
$ipLite->setKey('bda70bb41d690a240d19a0a6c998743d8ebec14a3867b7dcfe121d094ce35b91');
 
//Get errors and locations
$locations = $ipLite->getCity($ip);
$errors = $ipLite->getError();
 

$location_array = array();
$location_array['location'] = $locations;
$location_array['errors'] = $errors;

$json_location = json_encode($location_array);

// Save to db
DB::table('_trackers_data')
->where("id","=",$tracker_data_id)
->update(array(
'ip_location' => $json_location
)
);


// Update job status
DB::table('_trackers_tasks_current')
->where("id", "=", $task_id)
->update(array(
'act' => 1 )
);

}







}