<?php 

define('URL', 'http://test.local//');  
$config = array(
	'dbname'   => 'blog',
);

$connection=  new MongoClient();

$db = $connection->selectDB($config['dbname']);


$table = $db->selectCollection('posts');

$status = (empty($_GET['status'])) ? 'dashboard':$_GET['status'];

if ($status) {

            if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
                $article               = array();
                $article['title']      = $_POST['title'];
                $article['html']    = $_POST['content'];
				$article['content']    =$_POST['content'];

                $article['saved_at'] = new MongoDate();

                if ( empty($article['title']) || empty($article['content']) ) {
                    $data['status'] = 'Заполните все поля.';
                }else {
                    // then create a new row in the table
                    $table->insert($article);
                    $data['status'] = 'Документ успешно добавлен.';
                }
            }
        
    }

?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Blog php and Mongo</title>
	<link rel="stylesheet" href="css/font-awesome.css">
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/skeleton.css">
	<link rel="stylesheet" href="css/styles.css">
</head>
<body>

	<div class="container">
<div class="span10" id="post-admin">
	<?php
		if (isset($data['status'])) {
			echo '	<div class="alert alert-success">';
			echo($data['status']);
			echo '	</div>';
		}
	?>
	<h1>Создать новую статью</h1>

	<form action="?status=create" method="post">
		<div class="row">
			<div class="six columns">
				<label for="Title">Заголовок</label>
		    	<input type="text" class="u-full-width" name="title" id="title" required="required" />
		  	</div>
	  	</div>
	  	<div class="row">
			<label for="content">Основной текст</label>
			<p><textarea name="content" id="content" cols="40" rows="8" class="u-full-width"></textarea></p>
		</div>
		<div class="submit"><input type="submit" class="button-primary" name="btn_submit" value="Сохранить"/></div>
	</form>
</div>
</body>
</html>

