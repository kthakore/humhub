<?php

/**
 * CommentModule adds the comment content addon functionalities.
 *
 * @package humhub.modules_core.comment
 * @since 0.5
 */
class CommentModule extends CWebModule {

    /**
     * On content deletion make sure to delete all its comments
     *
     * @param CEvent $event
     */
    public static function onContentDelete($event) {

        foreach (Comment::model()->findAllByAttributes(array('object_model' => get_class($event->sender), 'object_id' => $event->sender->id)) as $comment) {
            $comment->delete();
        }
    }

    /**
     * On User delete, also delete all comments
     *
     * @param CEvent $event
     */
    public static function onUserDelete($event) {

        foreach (Comment::model()->findAllByAttributes(array('created_by' => $event->sender->id)) as $comment) {
            $comment->delete();
        }
        return true;
    }

    /**
     * On run of integrity check command, validate all module data
     *
     * @param CEvent $event
     */
    public static function onIntegrityCheck($event) {

        $integrityChecker = $event->sender;
        $integrityChecker->showTestHeadline("Validating Comment Module (" . Comment::model()->count() . " entries)");

        // Loop over all comments
        foreach (Comment::model()->findAll() as $c) {

            if ($c->getUnderlyingObject() === null) {
                $integrityChecker->showFix("Deleting comment id " . $c->id . " without existing target!");
                if (!$integrityChecker->simulate)
                    $c->delete();
            }
        }
    }

    /**
     * On init of the WallEntryLinksWidget, attach the comment link widget.
     *
     * @param CEvent $event
     */
    public static function onWallEntryLinksInit($event) {

        $contentMeta = $event->sender->object->contentMeta;

        $event->sender->addWidget('application.modules_core.comment.widgets.CommentLinkWidget', array(
            'modelName' => $contentMeta->object_model,
            'modelId' => $contentMeta->object_id,
                ), array('sortOrder' => 10)
        );
    }

    /**
     * On init of the WallEntryAddonWidget, attach the comment widget.
     *
     * @param CEvent $event
     */
    public static function onWallEntryAddonInit($event) {

        $contentMeta = $event->sender->object->contentMeta;

        $event->sender->addWidget('application.modules_core.comment.widgets.CommentsWidget', array(
            'modelName' => $contentMeta->object_model,
            'modelId' => $contentMeta->object_id,
                ), array('sortOrder' => 20)
        );
    }

}