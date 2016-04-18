<?php 

define('URL', 'http://test.local//');  
$config = array(
	'dbname'   => 'blog',
);

$connection=  new MongoClient();

$db = $connection->selectDB($config['dbname']);


$table = $db->selectCollection('posts');


$id   = $_REQUEST['id'];

if (strlen($id) == 24){
			$id = new \MongoId($id);
		}

$cursor  = $table->find(array('_id' => $id));

$article = $cursor->getNext();

$status = (empty($_GET['status'])) ? 'dashboard':$_GET['status'];

if ($status) {

	$id   = $_REQUEST['id'];
	$id = array('_id' => new MongoId($id) );
	$data['status'] =null;

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
	        $table->update($id,$article);
	        $data['status'] = 'Документ успешно обновлен.';
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
			echo '<a href="list.php" class="alert-link pull-right"> &larr; Вернуться назад</a>';
			echo '	</div>';
		}
	?>
	<h1>Редактировать статью</h1>

	<form action="" method="post">
		<div class="row">
			<div class="six columns">
				<label for="Title">Заголовок</label>
		      	<input type="text" class="u-full-width" name="title" id="title" required="required" value ="<?php echo $article['title']; ?>"/>
	      	</div>
	    </div>
	    <div class="row">
			<label for="content">Основной текст</label>
			<textarea name="content" id="content" cols="40" rows="16"
				class="u-full-width"><?php echo $article['content']; ?></textarea>

			<div class="submit"><input type="submit" class="button-primary" name="btn_submit" value="Сохранить"/></div>
		</div>
	</form>


</div>
	
</div>

</body>
</html>

