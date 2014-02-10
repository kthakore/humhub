<li>
    <form action="" class="dropdown-controls">
        <input type="text" id="search-menu-search"
               class="form-control"
               autocomplete="off"
               placeholder="<?php echo Yii::t('base', 'Search for users, spaces and posts'); ?>">

        <div class="search-reset" id="search-search-reset"><i
                class="icon-remove-sign"></i></div>
    </form>
</li>

<script type="text/javascript">
    /**
     * Open search menu
     */
    $('#search-menu').click(function () {

        // use setIntervall to setting the focus
        var searchFocus = setInterval(setFocus, 10);

        function setFocus() {

            // set focus
            $('#search-menu-search').focus();
            // stop interval
            clearInterval(searchFocus);
        }

    })
</script>