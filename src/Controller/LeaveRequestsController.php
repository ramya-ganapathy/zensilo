<?php 
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;
use Cake\Routing\Router;

class LeaverequestsController extends UsersController
{

    public function request($id = 0, $action = '')
    {
        $this->LeaveRequest = TableRegistry::get('leave_requests');
        $this->Leavetype = TableRegistry::get('leave_types');
        
        $user_id = $this->Auth->user('id'); 
        $company_id = $this->Auth->user('parent_id'); 
		
        if($action == 'delete')
        {
            $this->LeaveRequest->delete($this->LeaveRequest->get($id));
            $this->Flash->success('Leave request has been deleted successfully!!');
            $this->redirect(array("action" => 'request'));
        }
        elseif ($this->request->is('post') )
        { 
			
            $request = $this->LeaveRequest->newEntity();

            $this->request->data['user_id'] = $user_id;
            $this->request->data['company_id'] = $company_id;

            $request = $this->LeaveRequest->patchEntity($request, $this->request->data);
            
            if ($this->LeaveRequest->save($request)) {
				
                $this->Flash->success(__('The request has been saved.'));
                return $this->redirect(['action' => 'request']);
            }
            $this->Flash->error(__('Unable to add the user.'));
        }
        elseif($id)
        {
            $request = $this->LeaveRequest->get($id);
            $this->set('request', $request);
        }

		$requests =  $this->LeaveRequest->find('all')->leftJoin('leave_types', 'leave_types.id = leave_requests.type_id')
                    ->where(['user_id' => $user_id])->order(['leave_requests.id' => 'DESC'])
                    ->select(['leave_requests.id','leave_requests.no_of_days','leave_requests.start_date','leave_requests.end_date','leave_requests.reason','leave_requests.status','leave_requests.created','leave_types.type']);

        $leavetype =  $this->Leavetype->find('all')->where(['company_id' => $company_id]);

        $this->set('requests', $requests);
        $this->set('leavetype', $leavetype);
    }

    public function response($id = 0, $action = '')
    {
        $this->LeaveRequest = TableRegistry::get('leave_requests');
        
        $user_id = $this->Auth->user('id');
        
        if($action == 'accept')
        {
            $accept = $this->LeaveRequest->get($id);
            $accept->status = 1;
            if($this->LeaveRequest->save($accept)){
                $this->Flash->success('Leave request has been accepted successfully!!');
                $this->redirect(array("action"=>'response'));
            }
        }
        elseif($id)
        {
            $request = $this->LeaveRequest->find('all')
            ->leftJoin('users', 'users.id = leave_requests.user_id')
            ->leftJoin('leave_types', 'leave_types.id = leave_requests.type_id')
            ->where(['leave_requests.id' => $id,'users.lead_id' => $user_id])
            ->select(['leave_requests.id','leave_requests.no_of_days','leave_requests.start_date','leave_requests.end_date','leave_requests.reason','leave_requests.status','leave_requests.created','users.username','leave_types.type']);;
            $this->set('request', $request);
        }

        if ($this->request->is('post') )
        { 
            $reject = $this->request->data;

            if($reject['fmaction'] == 'reject'){

                $rejects = $this->LeaveRequest->get($reject['id']);
                $rejects->status = 2;
                $rejects->reject_reason = $reject['reason'];
                if($this->LeaveRequest->save($rejects)){
                    $this->Flash->success('Leave request has been rejected successfully!!');
                    $this->redirect(array("action"=>'response'));
                }
            }
        }

        $requests =  $this->LeaveRequest->find('all')->order(['leave_requests.status' => 'ASC','leave_requests.id' => 'DESC'])
        ->leftJoin('users', 'users.id = leave_requests.user_id')
        ->leftJoin('leave_types', 'leave_types.id = leave_requests.type_id')
        ->where(['users.lead_id' => $user_id])
        ->select(['leave_requests.id','leave_requests.no_of_days','leave_requests.start_date','leave_requests.end_date','leave_requests.reason','leave_requests.status','leave_requests.created','users.username','leave_types.type']);

        $this->set('requests', $requests);
        $this->set('requestid', $id);
    }

    public function info()
    {

    }


    public function getUserLeave($id = 0)
    {
        $result = array();

        $this->LeaveType = TableRegistry::get('leave_types');
        $this->LeaveRequest = TableRegistry::get('leave_requests');
        $this->Settings = TableRegistry::get('settings');
        $this->User = TableRegistry::get('users');

        $user_id = $this->Auth->user('id');
        $company_id = $this->Auth->user('parent_id');

        if($id > 0){

        $year_stare =  $this->Settings->find('all')->where(['company_id' => $company_id,'name' => 'YEARSTARTDATE'])->first();
        $year_end =  $this->Settings->find('all')->where(['company_id' => $company_id,'name' => 'YEARENDDATE'])->first();

        $types =  $this->LeaveType->find('all')->where(['company_id' =>  $company_id])->toArray();


        $leaves_taken = $this->LeaveRequest->find('all')->where(['start_date >=' => $year_stare->value ,'end_date <=' => 
            $year_end->value,'user_id' => $id,'status' => 1])->toArray();
        
        $leaves_next = $this->LeaveRequest->find('all')->where(['user_id' => $id,'status' => 1,'start_date >' => $year_stare->value,
            'end_date >' => $year_end->value])->toArray();

        $leaves_last = $this->LeaveRequest->find('all')->where(['user_id' => $id,'status' => 1,'start_date <' => $year_stare->value,
            'end_date >=' => $year_stare->value])->toArray();

        foreach ($types as $key => $value) {
              
            $result[$value->id]['name'] = $value->type;
            $result[$value->id]['max'] = $value->max_allowed_days;
            $result[$value->id]['taken'] = 0;
        }

        foreach ($leaves_taken as $key => $value) {

            $result[$value->type_id]['taken'] += $value->no_of_days;
        }

        foreach ($leaves_last as $key => $value) {

            $d1 = strtotime($year_stare->value);
            $d2 = strtotime($value->end_date);
            $datediff = $d2 - $d1;
            $dfdays =  floor($datediff / (60 * 60 * 24));
            $result[$value->type_id]['taken'] += ($dfdays + 1);

        }

        foreach ($leaves_next as $key => $value) {

            $d1 = strtotime($year_end->value);
            $d2 = strtotime($value->end_date);
            $datediff = $d2 - $d1;
            $dfdays =  floor($datediff / (60 * 60 * 24));
            $result[$value->type_id]['taken'] += ( $dfdays + 1 );

        }

        }else{

            $result =  $this->User->find('all')->where(['lead_id' => $user_id])->select(['id','username'])->toArray();

        }
       
        echo json_encode($result); exit;
               
    }
   
}