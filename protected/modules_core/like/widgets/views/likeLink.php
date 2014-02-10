<?php
/**
 * This view is used by the LikeLinkWidget to inject a link to the
 * Wall Entry Controls.
 *
 *
 * @property Boolean $currentUserLiked indicates if the current user liked this.
 * @property String $id is a unique Id on Model and PK e.g. (Post_1)
 * @property Array $likes all like objects existing for this object.
 *
 * @package humhub.modules_core.like
 * @since 0.5
 */
?>
<a href="#" id="<?php echo $id . "-LikeLink"; ?>" class="like likeAnchor"
   style="<?php if ($currentUserLiked): ?>display:none<?php endif; ?>">Like</a>
<a href="#" id="<?php echo $id . "-UnlikeLink"; ?>" class="unlike likeAnchor"
   style="<?php if (!$currentUserLiked): ?>display:none<?php endif; ?>">Unlike</a>

<?php

$userlist = ""; // variable for users output
$maxUser = 5; // limit for rendered users inside the tooltip

// if the current user also likes
if ($currentUserLiked == true) {
    // if only one user likes
    if (count($likes) == 1) {
        // output, if the current user is the only one
        $userlist = Yii::t('LikeModule.base', '<strong>You</strong> like this.');
    } else {
        // output, if more users like this
        $userlist .= Yii::t('LikeModule.base', '<strong>You</strong><br>');
    }
}

for ($i = 0; $i < count($likes); $i++) {

    // if only one user likes
    if (count($likes) == 1) {
        // check, if you liked
        if ($likes[$i]->getUser()->guid != Yii::app()->user->guid) {
            // output, if an other user liked
            $userlist .= "<strong>" . $likes[$i]->getUser()->displayName . "</strong>" . Yii::t('base', ' likes this.');
        }

    } else {

        // check, if you liked
        if ($likes[$i]->getUser()->guid != Yii::app()->user->guid) {
            // output, if an other user liked
            $userlist .= "<strong>" . $likes[$i]->getUser()->displayName . "</strong><br>";
        }

        // check if exists more user as limited
        if ($i == $maxUser) {
            // output with the number of not rendered users
            $userlist .= Yii::t('LikeModule.base', 'and {count} more like this.', array('{count}' => (intval(count($likes) - $maxUser))));

            // stop the loop
            break;
        }

    }
}
?>

<?php

// get class name and model id from $id variable
list($className, $modelId) = explode("_", $id);

?>

<?php if (count($likes) > 0) { ?>

    <!-- Create link to show all users, who liked this -->
    <a href="<?php echo $this->createUrl('//like/like/userlist', array('className' => $className, 'id' => $modelId)); ?>"
       class="tt" data-toggle="modal"
       data-placement="top" title="" data-target="#globalModal"
       data-original-title="<?php echo $userlist; ?>">(<span
            class="<?php echo $id . "-LikeCount"; ?>">0</span>)</a>
<?php } else { ?>
    (<span class="<?php echo $id . "-LikeCount"; ?>">0</span>)
<?php } ?>


<script type="text/javascript">

    // show Tooltips on elements inside the views, which have the class 'tt'
    $('.tt').tooltip({html: true});

</script>