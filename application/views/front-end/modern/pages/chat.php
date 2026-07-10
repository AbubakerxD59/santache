<main>
    <section class="container py-5">
        <div class="row">
            <div class="col-md-3 myaccount-navigation py-3">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-12 d-flex gap-2">
                        <div class="col-md-4 p-0">
                            <div class="card chat-theme-light chat-scroll chat-min">
                                <select name="select_user_id[]" id="chat_user" class="search_user w-100 mt-2" data-placeholder="Type search for support team">

                                </select>
                                <div id="add-scroll-js">
                                    <div class="card-header chat-card-header text-color mt-4">
                                        <h4 class="fw-semibold">Personal Chat</h4>
                                    </div>
                                    <div class="chat-card-body">
                                        <ul class="list-unstyled list-unstyled-border chat-list-unstyled-border">
                                            <?php
                                            // Create an array of supporter IDs for quick lookup
                                            $supporter_ids = array_column($supporters, 'userto_id');

                                            if (!empty($users)) {
                                                $has_users = false;
                                                foreach ($users as $user) {
                                                    // Skip if the user is a supporter
                                                    if (in_array($user['opponent_user_id'], $supporter_ids)) {
                                                        continue;
                                                    }

                                                    if ($user['id'] == $_SESSION['user_id']) {
                                                        $has_users = true;
                                            ?>
                                                        <li class="media">
                                                            <div class="media-body">
                                                                <div class="chat-person" data-picture="" data-type="person" data-id="<?= $user['opponent_user_id'] ?>"><i class="<?= ($user['is_online'] == 1) ? 'fa fa-circle text-success' : 'fa fa-circle'; ?> "></i> <?= $user['opponent_username'] ?> (You)</div>
                                                            </div>
                                                        </li>
                                                    <?php } elseif (isset($user['id']) && !empty($user['id']) && $user['id'] != '' && $user['id'] != $_SESSION['user_id']) {
                                                        $has_users = true;
                                                    ?>
                                                        <li class="media">
                                                            <div class="media-body">
                                                                <div data-unread_msg="<?= $user['unread_msg'] ?>" class="chat-person <?= ($user['unread_msg'] > 0) ? 'new-msg-rcv' : ''; ?>" data-picture="<?= $user['picture'] ?>" data-type="person" data-id="<?= $user['opponent_user_id'] ?>"><i class="<?= ($user['is_online'] == 1) ? 'fa fa-circle text-success' : 'fa fa-circle'; ?> "></i> <?= $user['opponent_username'] ?>
                                                                    <?= ($user['unread_msg'] > 0) ? (($user['unread_msg'] > 9) ? '<div class="badge-chat">9 +</div>' : '<div class="badge-chat">' . $user['unread_msg'] . '</div>') : ''; ?>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    <?php }
                                                }
                                                if (!$has_users) { ?>
                                                    <p class="card-body p-0 px-5 text-muted">It seems there are no chats available at the moment</p>
                                                <?php }
                                            } else { ?>
                                                <p class="card-body p-0 px-5 text-muted">It seems there are no chats available at the moment</p>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <div class="card-header chat-card-header d-flex text-color">
                                        <h4 class="fw-semibold"><?= !empty($this->lang->line('support_chat')) ? $this->lang->line('support_chat') : 'Support Team '; ?></h4>
                                    </div>
                                    <div class="chat-card-body">
                                        <ul class="list-unstyled list-unstyled-border chat-list-unstyled-border">
                                            <?php if (!empty($supporters)) {
                                                foreach ($supporters as $supporter) {
                                                    $date = strtotime('now');
                                                    $to_id = $this->session->userdata('user_id');

                                                    if ($to_id == $supporter['user_permission_id']) {
                                                        $supporter['is_online'] = 1;
                                                    } else {
                                                        if ($supporter['last_online'] > $date) {
                                                            $supporter['is_online'] = 1;
                                                        } else {
                                                            $supporter['is_online'] = 0;
                                                        }
                                                    }
                                            ?>
                                                    <li class="media">
                                                        <div class="media-body">
                                                            <div class="chat-person" data-id="<?= $supporter['userto_id'] ?>" data-type="person">
                                                                <i class="<?= ($supporter['is_online'] == 1) ? 'fa fa-circle text-success' : 'fa fa-circle'; ?> "></i> <?= $supporter['username'] ?>
                                                            </div>
                                                        </div>
                                                    </li>

                                                <?php }
                                            } else { ?>
                                                <p class="card-body p-0 px-5 text-muted">No support team available </p>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-8 col-lg-9" id="chat_area_wait">
                        </div>

                        <div class="col-md-8 d-none p-0" id="chat_area">
                            <div class="card chat-box chat-theme-light chat-min " id="mychatbox2">
                                <div class="align-items-center card-header chat-card-header d-flex">
                                    <div class="mr-3" id="chat-avtar-main">#</div>
                                    <div class="media-body">
                                        <div class="mt-0 mb-1 font-weight-bold text-color" id="chat_title"></div>
                                        <div class="text-small font-600-bold" id="chat_online_status"></div>
                                    </div>

                                </div>
                                <div id="chat-box-content" class="chat-bg card-body chat-scroll chat-content">
                                    <div class="chat_loader">Loading...</div>
                                </div>
                                <div class="card-body d-none" id="chat-dropbox">
                                    <div class="dropzone" id="myAlbum"></div>
                                    <div class="text-center mt-3">
                                        <button class="btn btn-danger shadow-none" onclick="closeDropZone();"><?= !empty($this->lang->line('close')) ? $this->lang->line('close') : 'Close'; ?>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-control theme-inputs d-none" id="chat-input-textarea-result"></div>
                                <div class="card-footer chat-form px-3">
                                    <form id="chat-form2" autocomplete="off">
                                        <div class="row">
                                            <div class="input-group">
                                                <input type="hidden" id="opposite_user_id" name="opposite_user_id" value="">
                                                <input type="hidden" id="my_user_id" name="my_user_id" value="<?= $_SESSION['user_id'] ?>" data-picture="">
                                                <input type="hidden" id="chat_type" name="chat_type" value="">
                                                <textarea class="form-control theme-inputs" id="chat-input-textarea" rows="1" name="chat-input-textarea"></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <span class="input-group-append ">
                                                <div class="form-group">
                                                    <a class="bg-success go-to-bottom-btn text-center btn btn-arrow">
                                                        <i class="fs-20 uil uil-arrow-down"></i>
                                                    </a>

                                                    <button class="btn btn-danger btn-send-msg">
                                                        <i class="fa fa-paper-plane fs-13"></i>
                                                    </button>

                                                    <button class="btn-file btn btn-primary" onclick="showDropZone();">
                                                        <i class="fa fa-file"></i>
                                                    </button>
                                                </div>
                                            </span>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal" tabindex="-1" role="dialog" id="chat-search-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Search</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="modal-part" id="modal-search-msg-part">
                            <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_search')) ? $this->lang->line('label_search') : 'Search'; ?></div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-search"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="in-chat-search" id="in-chat-search">
                                    </div>
                                </div>

                                <div class="col-md-12 d-none" id="show-search-result">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4><?= !empty($this->lang->line('label_search_result')) ? $this->lang->line('label_search_result') : 'Search Result'; ?></h4>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-unstyled list-unstyled-border" id="search-result">

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</main>
<!-- chat -->
<script type="module" src="<?= THEME_ASSETS_URL . 'js/components-chat-box.js' ?>"></script>