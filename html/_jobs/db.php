<?php
/** The name of the database for WordPress */
if (empty($_ENV['DB_NAME'])) {
	define('DB_NAME', 'wrlt-bog');
} else {
	define('DB_NAME', $_ENV['DB_NAME']);
}

/* MySQL database username */
if (empty($_ENV['DB_USER'])) {
define('DB_USER', 'root');
	} else {
define('DB_USER', $_ENV['DB_USER']);
}
/** MySQL database password */
if (empty($_ENV['DB_PASSWORD'])) {
define('DB_PASSWORD', '');
	} else {
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);
}

/** MySQL hostname */
if (empty($_ENV['DB_HOST'])) {
define('DB_HOST', 'localhost');
	} else {
define('DB_HOST', $_ENV['DB_HOST']);
}

define('FOLDER_LOGS', 'logs'); //relative to this file
define('FOLDER_SQL', 'sql'); //relative to this file

//increasing time limits and memory only for this script to allow long queries.
ini_set('memory_limit', '5120M');
set_time_limit ( 0 );

//start database connector
$db = dbconn(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (!$db || is_array($db)) {// error when trying to connect
    echo '{"status":"100","response" :"Unable to connect, error: '.trim(preg_replace('/\s+/', ' ', $db['error'])).'", "errorno":"'.trim(preg_replace('/\s+/', ' ', $db['errorno'])).'"}';
	exit;
} else if($db && !is_array($db)) {// no errors, connected, proceed
	//set to UTF8
	$db->set_charset("utf8");
	//check current version
	$checkquery = 'SELECT * FROM `db_version` ORDER BY `version` DESC LIMIT 1';
	$checkresult = mysqli_query($db, $checkquery);
	if ($checkresult && (mysqli_num_rows($checkresult) > 0)) {//found result
		$row = $checkresult->fetch_array(MYSQLI_ASSOC);
		$currentversion = $row['version'];
		$nextversion = $currentversion+1;
		$logtext = 'Found version: '.$currentversion.', will try to update to: '.$nextversion;
		RecruitmentPlugin_log('version', $logtext);
	} else {
		$nextversion = 1;
		$logtext = 'Not found version or table. Starting initialization.';
		RecruitmentPlugin_log('version', $logtext);
	}
		// check if there is a file to update
		$dbms_schema = $nextversion.'.sql';
		$dbms_file = FOLDER_SQL.'/'.$nextversion.'.sql';

		if (!file_exists($dbms_file)) {// no file found
			$logtext = 'Checked for possible update to version: '.$nextversion.', on file '.$dbms_file.'. Nothing updated';
			RecruitmentPlugin_log('version', $logtext);
			echo '{"status":"000","response" :"Checked for possible update to version: '.$nextversion.'. Nothing updated"}';
			exit;
		}

		//file found proceed
		$dbms_stream = @fopen($dbms_file,"r");
		if ($dbms_stream === false) {// Unable to open, handle error
			$logtext = 'Unable to open file: '.$dbms_file.'. Nothing updated';
			RecruitmentPlugin_log('version', $logtext);
			$logtext = 'Unable to open file: '.$dbms_file.'. Nothing updated';
			RecruitmentPlugin_log('file_error', $logtext);
			echo '{"status":"200","response" :"Unable to open file: '.$dbms_file.'. Nothing updated"}';
			exit;
		}

		//is the file empty?
		$dbms_file_size = @filesize($dbms_file);
		if(empty($dbms_file_size)) {
			$logtext = 'Empty file on version: '.$nextversion.', on file '.$dbms_file.'.  Nothing updated';
			RecruitmentPlugin_log('version', $logtext);
			echo '{"status":"000","response" :"Empty file  on version: '.$nextversion.'.  Nothing updated"}';
			exit;
		}

		//is the file readable?
		$sql_query = @fread($dbms_stream, $dbms_file_size);
		if ($sql_query === false) {// Unable to read, handle error
			$logtext = 'Unable to read on version: '.$nextversion.', on file: '.$dbms_file.'. Nothing updated';
			RecruitmentPlugin_log('version', $logtext);
			$logtext = 'Unable to read file: '.$dbms_file.'. Nothing updated';
			RecruitmentPlugin_log('file_error', $logtext);
			echo '{"status":"200","response" :"Unable to read file: '.$dbms_file.'. Nothing updated"}';
			exit;
		}

		// trim, remove comments, remarks
		//$sql_query = remove_comments($sql_query);
		$sql_query = trim($sql_query);

		//do we have any queries to execute?
		if(empty($sql_query)) {
			$logtext = 'No SQL commands on version: '.$nextversion.', on file '.$dbms_file.'.  Nothing updated';
			RecruitmentPlugin_log('version', $logtext);
			echo '{"status":"000","response" :"No SQL commands on version: '.$nextversion.'.  Nothing updated"}';
			exit;
		}

		//proceed
		$sql_query = remove_remarks($sql_query);
		$sql_query = split_sql_file($sql_query, ';');
		$errors = 0;
		foreach($sql_query as $sql){
			if(!@mysqli_query($db, $sql)) {
				$logtext = 'Error on SQL command: '.$sql.' on version: '.$nextversion.', on file '.$dbms_file.', - skipped';
				RecruitmentPlugin_log('sql_error', $logtext);
				$errors++;
			}
		}
		if($errors >0 ) {
			$logtext = 'Updated partially, with '.$errors.' errors: '.$nextversion.', on file '.$dbms_file.'.  Nothing updated';
			RecruitmentPlugin_log('version', $logtext);
			echo '{"status":"050","response" :"Updated partially with '.$errors.' errors on version: '.$nextversion.'."}';
		} else {
			$logtext = 'Updated without errors on version: '.$nextversion.', on file '.$dbms_file.'.  Nothing updated';
			RecruitmentPlugin_log('version', $logtext);
			echo '{"status":"000","response" :"Updated without errors on version: '.$nextversion.'."}';
		}
		//update with version
		$update_sql = "INSERT INTO `db_version` VALUES ('',CURRENT_TIMESTAMP);";
		mysqli_query($db, $update_sql);
		unset($db);
		exit;
}


//database connector
function dbconn($host=DB_HOST, $user=DB_USER, $pass=DB_PASSWORD, $dbname=DB_NAME)
{
	$db = mysqli_init();
	if (!$db) {
		$logtext = 'Mysqli_init failed';
		RecruitmentPlugin_log('db_error', $logtext);
		return array('result'=>false, 'error'=>$logtext, 'errorno'=>'1035');
	}
	if (!$db->options(MYSQLI_OPT_CONNECT_TIMEOUT, 2)) {
		$logtext = 'Setting MYSQLI_OPT_CONNECT_TIMEOUT failed';
		RecruitmentPlugin_log('db_error', $logtext);
		return array('result'=>false, 'error'=>$logtext, 'errorno'=>'1035');
	}
	if (!@$db->real_connect($host, $user, $pass, $dbname)) {
		$logtext = 'Unable to connect to database: '.$dbname.', on host: '.$host.' under credentials: '.$user.' and '.$pass.' - error: '. mysqli_connect_error();
		RecruitmentPlugin_log('db_error', $logtext);
		return array('result'=>false, 'error'=>mysqli_connect_error(), 'errorno'=>mysqli_connect_errno($db));
	}
	return $db;
}

// remove sql comments, remarks out of an uploaded sql file
function remove_remarks(&$output)
{
   $lines = explode("\n", $output);
   $output = "";

   // try to keep mem. use down
   $linecount = count($lines);

   $in_comment = false;
   for($i = 0; $i < $linecount; $i++)
   {
      if( preg_match("/^\/\*/", preg_quote($lines[$i])) )
      {
         $in_comment = true;
      }

      if( !$in_comment )
      {
         $output .= $lines[$i] . "\n";
      }

      if( preg_match("/\*\/$/", preg_quote($lines[$i])) )
      {
         $in_comment = false;
      }
   }

   unset($lines);
   return $output;

}

// split_sql_file will split an uploaded sql file into single sql statements.
function split_sql_file($sql, $delimiter)
{
   // Split up our string into "possible" SQL statements.
   $tokens = explode($delimiter, $sql);

   // try to save mem.
   $sql = "";
   $output = array();

   // we don't actually care about the matches preg gives us.
   $matches = array();

   // this is faster than calling count($oktens) every time thru the loop.
   $token_count = count($tokens);
   for ($i = 0; $i < $token_count; $i++)
   {
      // Don't wanna add an empty string as the last thing in the array.
      if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0)))
      {
         // This is the total number of single quotes in the token.
         $total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
         // Counts single quotes that are preceded by an odd number of backslashes,
         // which means they're escaped quotes.
         $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);

         $unescaped_quotes = $total_quotes - $escaped_quotes;

         // If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.
         if (($unescaped_quotes % 2) == 0)
         {
            // It's a complete sql statement.
            $output[] = $tokens[$i];
            // save memory.
            $tokens[$i] = "";
         }
         else
         {
            // incomplete sql statement. keep adding tokens until we have a complete one.
            // $temp will hold what we have so far.
            $temp = $tokens[$i] . $delimiter;
            // save memory..
            $tokens[$i] = "";

            // Do we have a complete statement yet?
            $complete_stmt = false;

            for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++)
            {
               // This is the total number of single quotes in the token.
               $total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
               // Counts single quotes that are preceded by an odd number of backslashes,
               // which means they're escaped quotes.
               $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);

               $unescaped_quotes = $total_quotes - $escaped_quotes;

               if (($unescaped_quotes % 2) == 1)
               {
                  // odd number of unescaped quotes. In combination with the previous incomplete
                  // statement(s), we now have a complete statement. (2 odds always make an even)
                  $output[] = $temp . $tokens[$j];

                  // save memory.
                  $tokens[$j] = "";
                  $temp = "";

                  // exit the loop.
                  $complete_stmt = true;
                  // make sure the outer loop continues at the right point.
                  $i = $j;
               }
               else
               {
                  // even number of unescaped quotes. We still don't have a complete statement.
                  // (1 odd and 1 even always make an odd)
                  $temp .= $tokens[$j] . $delimiter;
                  // save memory.
                  $tokens[$j] = "";
               }

            } // for..
         } // else
      }
   }

   return $output;
}

//file logger
function RecruitmentPlugin_log($priority, $text)
{
	$log  = PHP_EOL."Log timestamp (local): ".date("Y-m-d, H:i:s").", Europe/Athens".PHP_EOL;
		if($text!='') {
			$log .= "Log data follows: ".PHP_EOL.$text;
		}
		$log .= PHP_EOL."-------------------------".PHP_EOL;

		//get the last two digits of the IP
		file_put_contents(FOLDER_LOGS.'/'.date("Y-m-d").'_'.$priority.'.lg', $log, FILE_APPEND | LOCK_EX);
}
?>
