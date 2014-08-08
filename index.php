<?php

	// Register a simple autoload function
	spl_autoload_register(function($className){
	    
		$className		=	ltrim($className, '\\');
    	$fileName  		= 	'';
    	$namespace 		= 	'';
	    if ($lastNsPos = strrpos($className, '\\')) {
	        $namespace = substr($className, 0, $lastNsPos);
	        $className = substr($className, $lastNsPos + 1);
	        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	    }
	    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

	    require_once( $fileName );
	});

	$email		=	'webloper@gmail.com';

	$gravatar 	=	new \Gravatar\Gravatar( $email );
 
	echo '<pre>';
	print_r($gravatar->__toString());
	echo '</pre>';
	die();

	//$names = $xml->xpath( '//entry/name/formatted' );
	//if ( is_array( $names ) && isset( $names[0] ) )
	//    echo (string) $names[0];

	//if ( is_array( $profile ) && isset( $profile['entry'] ) )
    //	echo $profile['entry'][0]['name']['formatted'];

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Gravatar</title>
	</head>
	<body>
		
	</body>
</html>