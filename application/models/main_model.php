<?php 

/**
* 
*/
class Main_model extends CI_Model
{
	function __construct()
	{
		# code...
		parent::__construct();
	}


	function check_login($username,$password,$table)
	{
		$this->db->select('password');
		$this->db->where('username',$username);
		$this->db->where('status','1');
		$query = $this->db->get($table);
		$row = $query->row();
		if($query->num_rows() > 0)
		{
			if($password == $row->password)
			{
				return TRUE;
			}
			else
			{
				return False;
			}
		}
		else
		{
			return false;
		}
	}

// **************** start comman functions ****************
	
	//function for create random string..
	function generateRandomString($length = 3) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
		  $randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString.rand(0,9);
	}

	function fetchbyid($id,$table)
	{
		$this->db->where('status','1');
		$this->db->where('id',$id);
		$query = $this->db->get($table);
		if($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return false;
		}
	}

	function fetchbyfield($field,$value,$table)
	{

		$this->db->where('status','1');
		$this->db->where($field,$value);
		$query = $this->db->get($table);
		if($query->num_rows() > 0)
		{
			//return only one row..
			return $query->row_array();
		}
		else
		{
			return false;
		}
	}

	function fetchalldata($table)
	{
		$this->db->where('status','1');
		$query = $this->db->get($table);
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}

	function fetchalldatadesc($table)
	{
		$this->db->where('status','1');
		$this->db->order_by('id','desc');
		$query = $this->db->get($table);
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();
			$num_rows = $query->num_rows();
			return array($data,$num_rows);
		}
		else
		{
			return false;
		}
	}

	function fetchlastactivedata($table)
	{
		$this->db->where('status','1');
		$this->db->order_by('id','desc');
		$this->db->limit('1');
		$query = $this->db->get($table);
		if($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return false;
		}
	}

	function fetchlastdata($table)
	{
		$this->db->order_by('id','desc');
		$this->db->limit('1');
		$query = $this->db->get($table);
		if($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return false;
		}
	}

	function updatedata($data,$id,$table)
	{
		$this->db->where('id',$id);
		$query = $this->db->update($table,$data);
		if($query)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	// function for insert data into table
	function insertdata($data,$table)
	{
		$query = $this->db->insert($table,$data);
		if($query)
		{
			return $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}

	//fetch all files order by modified time desc..
	function fetchallfiles($file_name = "", $user_name = "")
	{
		$this->db->like("file_name",$file_name);
		$this->db->like("created_by",$user_name);
		$this->db->where('status','1');
		$this->db->order_by('modify_time','desc');
		$query = $this->db->get('excel_file');
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();
			$num_rows = $query->num_rows();
			return array($data,$num_rows);
		}
		else
		{
			return false;
		}
	}

	//fetch all files order by modified time desc..
	function fetch_file_with_limit($file_name = "", $user_name = "",$limit)
	{
		$this->db->like("file_name",$file_name);
		$this->db->like("created_by",$user_name);
		$this->db->limit($limit);
		$this->db->where('status','1');
		$this->db->order_by('modify_time','desc');
		$query = $this->db->get('excel_file');
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();
			$num_rows = $query->num_rows();
			return array($data,$num_rows);
		}
		else
		{
			return false;
		}
	}


	//fetch all files order by modified time desc..
	function fetch_file_limit($file_name = "", $user_name = "", $i,$j)
	{
		$this->db->like("file_name",$file_name);
		$this->db->like("created_by",$user_name);
		$this->db->limit($j,$i);
		$this->db->where('status','1');
		$this->db->order_by('modify_time','desc');
		$query = $this->db->get('excel_file');
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();
			$num_rows = $query->num_rows();
			return array($data,$num_rows);
		}
		else
		{
			return false;
		}
	}
	

	//function for search user by name..
	function searchuser($name,$username,$role)
	{
		$this->db->where('status','1');
		$this->db->order_by('id','desc');
		$this->db->like('name',$name,"both");
		$this->db->like('username',$username,"both");
		$this->db->like('role',$role,"both");
		$query = $this->db->get('user');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}



	function fetch_user_name($username)
	{
		$this->db->where('username',$username);
		$query = $this->db->get('user');
		if($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return false;
		}
	}
}
?>