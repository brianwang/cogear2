<?php
$user = new User();
$user->id = $item->aid;
$user->find();
$item->author = $user;
event('parse',$item);
if (!($item->teaser OR $item->preview)) {
    $before = new Stack(array('name' => 'post.full.before'));
    $before->object($item);
    echo $before->render();
}
if (!$item->published && !$item->preview) {
    $item->class = $item->class ? $item->class . ' draft' : 'draft';
}
?>
<article class="post <?php echo $item->class ?> shd" id="post-<?php echo $item->id ?>">
    <div class="post-title">
        <?php
        $title = new Stack(array('name' => 'post.title'));
        $title->object($item);
        $title->name = '<h2>' . ($item->teaser ? '<a href="' . $item->getLink() . '">' . $item->name . '</a>' : $item->name) . '</h2>';
        if (!$item->preview) {
//            if (access('Post.delete', $item)) {
//                $title->delete = '<a class="post-delete sh" data-id="' . $item->id . '" href="' . $item->getLink('delete') . '"><i class="icon-remove"></i></a>';
//            }
//            if (access('Post.hide', $item)) {
//                $title->hide = '<a class="post-hide sh" data-id="' . $item->id . '" href="' . $item->getLink('hide') . '"><i class="icon-eye-' . ($item->published ? 'open' : 'close') . '"></i></a>';
//            }
            if (access('Post.edit', $item)) {
                $title->edit = '<a class="post-edit sh" data-id="' . $item->id . '" href="' . $item->getLink('edit') . '"><i class="icon-pencil"></i></a>';
            }
        }
        echo $title->render();
        ?>
    </div>
    <?php
    $before = new Stack(array('name' => 'post.before'));
    $before->object($item);
    echo '<div class="post-before">' . $before->render() . '</div>';
    ?>
    <div class="post-body">
        <?php echo $item->body; ?>
    </div>
    <?php
    $after = new Stack(array('name' => 'post.after'));
    $after->object($item);
    echo '<div class="post-after">' . $after->render() . '</div>';
    ?>
    <div class="post-info">
        <?php
        $info = new Stack(array('name' => 'post.info'));
        $info->object($item);
        $info->time = '<span class="post-time">' . df($item->created_date) . '</span>';
        echo $info->render();
        ?>
    </div>
</article>
<?php
if (!($item->teaser OR $item->preview)) {
    $after = new Stack(array('name' => 'post.full.after'));
    $after->object($item);
    echo $after->render();
}