<?php

/*
*
*	DB CLASS...
*
*/
	
	
	
/*
*
*
*
*
* socialmac
*  geekconnect
*
*/

function microtime_float() { return microtime(true); }

class DBLayer
{

	var $prefix;
	var $link_id;
	var $query_result;

	// For statistics and debugging
	var $debug;
	var $saved_queries;
	var $num_queries;

	function __construct(
		$db_host="localhost", 
		$db_username="root", 
		$db_password="", 
		$db_name="socialmac", 
		$db_prefix="", $p_connect=false, $debug = false)
	{
  	// Overrides for Garrett's local testing server
    if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost:8888') {
      $db_name = 'macsocial';
      $db_username = 'root';
      $db_password = 'root';
      $db_prefix = '';
    }
		$this->prefix = $db_prefix;
		$this->debug = $debug;

		if ($p_connect) {
            @$this->link_id = mysqli_pconnect($db_host, $db_username, $db_password);
        } else {
            @$this->link_id = mysqli_connect($db_host, $db_username, $db_password);
        }

        /*if ( $_SERVER['REMOTE_ADDR'] == '24.3.59.194' )
				$this->zzSaveIT();*/

		if($this->link_id)
		{
                    if (@mysqli_select_db($this->link_id, $db_name)) {
                return $this->link_id;
            } else {
                error('Unable to select database. MySQL reported: ' . mysqli_error($this->link_id), __FILE__, __LINE__, 1);
            }
        }
		else{
            error('Unable to connect to MySQL server.', __FILE__, __LINE__, 1);
        }
	}
	
	/*function zzSaveIT()
	
		{
			
			if ( file_exists( INCL_PATH . "mysql.log.txt" ) )
				$i = ( file_get_contents( INCL_PATH . "mysql.log.txt" ) + 1 );
			else
				$i = 1;
			
			$fh = fopen( INCL_PATH . "mysql.log.txt", "w" );
			fwrite( $fh, $i );
			fclose( $fh );
			
		} // ends method...*/
	
	function query($sql)
	{
		$mt_start = microtime_float();

		$this->query_result = @mysqli_query($this->link_id,$sql);
		
		//if (microtime_float()-$mt_start > 5.5)
		//	print("### SLOW QUERY (".number_format(microtime_float()-$mt_start, 2)." sec; {$_SERVER['REQUEST_URI']}): " . $sql);

		if ($this->query_result)
		{
			if ($this->debug)
			{
				print("@@@ SUCCESSFUL QUERY: {$sql}");
				$this->saved_queries[] = array($sql, microtime()-$mt_start);
			}
			$this->num_queries++;
			return $this->query_result;
		}
		else
		{
                    if ($this->debug) {
                $this->saved_queries[] = array($sql, 0);
            }

            print("### FAILED QUERY ({$_SERVER['REQUEST_URI']}): {$sql}; failure: " . mysqli_error($this->link_id) );
			
			return false;
		}	
	}

	function goto_first($query_result = 0)
	{
            if ($query_result) {
            @mysqli_data_seek($query_result, 0);
        }
    }

	function result($query_result = 0, $row = 0)
	{
            if (!$query_result) {
            return false;
        }

        if ($row) {
            @mysqli_data_seek($query_result, $row);
        }

        $cur_row = @mysqli_fetch_row($query_result);
		return $cur_row[0];
	}

	function fetch_all_result($query_result = 0)
	{
            if (!$query_result) {
            return false;
        }

        $rows = array();
        while ($row = $this->result($query_result)) {
            $rows[] = $row;
        }

        return $rows;
	}
	
	function fetch_array($query_result = 0, $resulttype = MYSQLI_NUM)
	{
		return ($query_result) ? @mysqli_fetch_array($query_result) : false;
	}
	
	function fetch_all_array($query_result = 0)
	{
            if (!$query_result) {
            return false;
        }

        $rows = array();
        while ($row = $this->fetch_array($query_result)) {
            $rows[] = $row;
        }

        return $rows;
	}
	function fetch_assoc($query_result = 0)
	{
		return ($query_result) ? @mysqli_fetch_assoc($query_result) : false;
	}
	function fetch_all_assoc($query_result = 0)
	{
            if (!$query_result) {
            return false;
        }

        $rows = array();
        while ($row = $this->fetch_assoc($query_result)) {
            $rows[] = $row;
        }

        return $rows;
	}
	

	function fetch_row($query_result = 0) { return ($query_result) ? @mysqli_fetch_row($query_result) : false; }

	function num_rows($query_result = 0) { return ($query_result) ? @mysqli_num_rows($query_result) : false; }

	function insert_id() { return ($this->link_id) ? @mysqli_insert_id($this->link_id) : false; }
	function affected_rows() { return ($this->link_id) ? @mysqli_affected_rows($this->link_id) : false; }

	function free_result($query_result = 0) { return ($query_result) ? @mysqli_free_result($query_result) : false; }

	function escape($str) { return mysqli_real_escape_string( $this->link_id,$str); }

	function close()
	{
            if ($this->link_id) {
                if ($this->query_result) {
                $this->free_result($this->query_result);
            }

            return @mysqli_close($this->link_id);
        }
        else {
            return false;
        }
    }



	function get_queries() { return $this->saved_queries; }
	function get_num_queries() { return $this->num_queries; }

	function error()
	{
		$result['error_sql'] = @current(@end($this->saved_queries));
		$result['error_no'] = @mysqli_errno($this->link_id);
		$result['error_msg'] = @mysqli_error($this->link_id);
		return $result;
	}
}

