<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
require __DIR__ . '/../../../core/Socket.php';
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\Socket;

use Amp\Delayed;
use Amp\Loop;
use function Amp\asyncCall;

require __DIR__ . '/../../../vendor/autoload.php';



/**
 * Dashboard.php
 *
 * @package     CI-ACL
 * @author      Steve Goodwin
 * @copyright   2015 Plumps Creative Limited
 */
class Test extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
    }
    
    public function welcome(){
        echo new Socket();
    }
    
    public function asyncCall_test(){
        
        asyncCall(function () {
            for ($i = 0; $i < 5; $i++) {
                echo( "mehar " . $i . "<br>");
                yield new Delayed(1000);
            }
        });
            
        asyncCall(function () {
            for ($i = 0; $i < 5; $i++) {
                echo ( "trinadh" . $i . "<br>");
                yield new Delayed(400);
            }
        });
            
         Loop::run();
                
    }
    
    public function event_loop_test(){
        print "Press Ctrl+C to exit..." . PHP_EOL;
        
        Loop::onSignal(SIGINT, function () {
            print "Caught SIGINT, exiting..." . PHP_EOL;
            exit(0);
        });
            
       Loop::run();
    }
    
    public function  run_socket() {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Socket()
                    )
                ),
            8080
            );
        
        $server->run();
    }
    
    public function test_socket(){
        $this->load->view('admin/admin/socket');
    }

}
?>