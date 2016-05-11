<?php
Class Voting_model extends IT_Model
{
	
	


	public function frontendGetVotingList($app_id,$comm_id){
		//get user
		$query = "SELECT client_sn FROM sys_user WHERE comm_id='".$comm_id."' AND app_id ='".$app_id."'";
		$member_sn = $this->it_model->runSql($query);

		if($member_sn['count']==0){
			return FALSE;
		}

		$member_sn = $member_sn['data'][0]['client_sn'];

		//runSql
		$today = date("Y-m-d");

		$sql_date  = " AND '".$today."' >= voting.start_date AND '".$today."' <= voting.end_date ";

		$sql_subquery =  " SELECT 
							count(*) as counts,voting_sn 
							FROM voting_record 
							WHERE user_sn = ".$member_sn." AND comm_id='".$comm_id."' 
							GROUP BY voting_sn ";

		$sql = "SELECT 
				voting.client_sn as sn,
				subject,
				description,
				start_date,
				end_date
				FROM voting LEFT JOIN (".$sql_subquery.") AS vr ON  voting.client_sn = vr.voting_sn
				WHERE vr.counts IS NULL".$sql_date." AND voting.is_del = 0 AND voting.comm_id='".$comm_id."'"  ;

		$result = $this->it_model->runSql($sql);

	
		return $result;
	}

	public function frontendGetVotingResultList($comm_id){
		//runSql
		$today = date("Y-m-d");

		$sql_date  = " AND  '".$today."' >= voting.end_date ";

		

		$sql = "SELECT 
				subject,
				description,
				start_date,
				end_date,
				client_sn as sn

				FROM voting 
				WHERE 1=1 ".$sql_date." AND is_del = 0 AND comm_id='".$comm_id."'" ;

		$result = $this->it_model->runSql($sql);

	
		return $result;
	}

	public function frontendGetVotingDetail($voting_sn = null,$comm_id){

		$sql = "SELECT * FROM voting WHERE client_sn ='".$voting_sn."' AND is_del=0 AND comm_id='".$comm_id."'";
		$voting = $this->it_model->runSql($sql);
		$voting = $voting['data'][0];

		$sql = "SELECT * FROM voting_option WHERE voting_sn ='".$voting_sn."' AND is_del=0 AND comm_id='".$comm_id."'";
		$voting_option = $this->it_model->runSql($sql);

		$voting['voting_option'] = $voting_option['data'];

		return $voting;

	}

	public function frontendGetVotingUpdate($voting_sn,$voting_option_sn,$user_sn,$user_id,$comm_id){

		$arr_data =array(
			"voting_sn" => $voting_sn,
			"option_sn" =>$voting_option_sn,
			"user_sn" =>$user_sn,
			"user_id" => $user_id,		
			"created" => date("Y-m-d H:i:s"),
			"comm_id" => $comm_id
		);		

		$re = $this->it_model->addData("voting_record",$arr_data);
		return $re;

	}

	public function votingRecord($voting_sn,$comm_id){

		//get voting info

		$sql="SELECT * FROM voting WHERE client_sn=".$voting_sn." AND comm_id='".$comm_id."'";
		$re = $this->it_model->runSql($sql);

		if($re['count'] == 0){
			return FALSE;
		}

		$re = $re['data'][0];

		$data =array("subject" => $re['subject'],
					"description" => $re['description'],
					"start_date" => $re['start_date'],
					"end_date" => $re['end_date'],				
					"options" => array(),
					"creater_user"=>null);


		if($re['user_sn']!=''){
			$query = "SELECT name FROM sys_user WHERE client_sn = ".$re['user_sn']." AND comm_id='".$comm_id."'";
			$post_user = $this->it_model->runSql($query);
			if($post_user['count'] > 0){
				$data['creater_user'] = $post_user['data'][0]['name'];
			}
		}

		$sql ="SELECT voting_option.client_sn AS option_sn,					
					IFNULL(voting_count,0) as voting_count,
					voting_option.text 
					FROM voting_option 
					LEFT JOIN 
    				(select option_sn,count(*) as voting_count from voting_record group by option_sn) AS vr ON voting_option.client_sn = vr.option_sn
					WHERE voting_option.voting_sn = ".$voting_sn." AND voting_option.is_del=0 AND comm_id = '".$comm_id."'";

		//echo $sql;die();

		$re = $this->it_model->runSql($sql);
		$re = $re['data'];	

		for($i=0;$i<count($re);$i++){

			$_arr = array(
				"option_sn" => $re[$i]['option_sn'],
				"option_text" => $re[$i]['text'],
				"voting_count" => $re[$i]['voting_count']
			);
			
			array_push($data['options'],$_arr);
		}

		return $data;

	}	


	
	

	
}

