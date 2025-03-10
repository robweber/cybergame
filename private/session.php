<?php
  require('initialize.php');
  function startSession($username){
    session_start();

    // Set a session variable to track the login status
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $username;

    $_SESSION['user_id'] = get_user_id($db, $username);
  }

  function checkSession(){
    session_start();
    // Session timeout duration in seconds
    $timeout = 900;

    // Check if a user is actually logged in
    if( isset( $_SESSION['loggedin']) && $_SESSION['loggedin'] === false){
      session_unset();
      session_destroy();
      // Redirect to the login page
      header("Location: ../login");
      exit();
    }

    // Check existing timeout variable
    elseif( isset( $_SESSION['lastaccess'] ) ) {
    	// Time difference since user sent last request
    	$duration = time() - intval( $_SESSION['lastaccess'] );

    	// Destroy if last request was sent before the current time minus last request
    	if( $duration > $timeout ) {
        session_unset();
        session_destroy();
        // Redirect to the login page
        header("Location: ../login");
        exit();
    	}
    }
  }

  function endSession(){
    session_start();  // Start the session

    // Destroy all session data
    session_unset();
    session_destroy();

    // Redirect to the login page
    header("Location: ../login");
    exit();
  }
?>
