<?php
/**
 * Created by Andreas Strobel
 * Date: 25.06.13
 */
?>
<div class="container">
    <!-- Example row of columns -->
    <div class="row">
        <div class="col-md-8">
            <?php
            $this->widget('application.modules_core.post.widgets.PostFormWidget', array(
                'target' => Wall::TYPE_USER, 'guid' => Yii::app()->user->guid
            ));
            ?>

            <?php
            $this->widget('application.modules_core.wall.widgets.WallStreamWidget', array(
                'type' => Wall::TYPE_DASHBOARD
            ));
            ?>
        </div>
        <div class="col-md-4">
            <?php
            $this->widget('application.modules_core.dashboard.widgets.DashboardSidebarWidget', array(
                'widgets' => array(
                    array('application.modules_core.activity.widgets.ActivityStreamWidget', array('type' => 'dashboard'), array('sortOrder' => 10)),
                )
            ));
            ?>
        </div>
    </div>

</div>
