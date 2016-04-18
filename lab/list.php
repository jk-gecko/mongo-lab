<?php 

define('URL', 'http://test.local//');  
$config = array(
	'dbname'   => 'blog',
);

$connection=  new MongoClient();

$db = $connection->selectDB($config['dbname']);


$table = $db->selectCollection('posts');

$cursor = $table->find();

$totalArticles = $cursor->count(); 

$status = (empty($_GET['status'])) ? '':$_GET['status'];

if ($status == 'delete') {
	$id = $_GET['id'];
	$id = array('_id' => new MongoId($id) );
    $status = $table->remove($id);
    if ($status ==TRUE ) {
        header("Location:list.php");
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
    <h1>Список документов</h1>
    <table class="u-full-width">
        <thead>
            <tr>
                <th>Заголовок</th>
                <th>Дата изменения</th>
                <th>Опции</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php while($cursor->hasNext()):
            $article = $cursor->getNext();?>
            <tr>
                <td><?php echo substr($article['title'], 0, 50) . '...'; ?></td>
                <td><?php echo date('Y-m-d H:i:s', $article['saved_at']->sec);
                ?></td>

                <td width="10%">
                    <a href="edit.php?status=edit&id=<?php echo $article['_id'];?>"><i class="fa fa-pencil-square-o"></i>Редактировать</a>

                </td>
                <td width="10%">
                     <a href="#" onclick="confirmDelete('<?php echo $article['_id']; ?>')"><i class="fa fa-times"></i> Удалить</a>
                </td>
            </tr>
            <?php endwhile;?>
        </tbody>
    </table>
</div>


</div>


<script type="text/javascript" charset="utf-8">
    function confirmDelete(articleId) {

        var deleteArticle = confirm('Вы уверены что хотите удалить эту статью?');

        if(deleteArticle){
            window.location.href = '?status=delete&id='+articleId;
        }
        return;
    }
</script>
</body>
</html>

