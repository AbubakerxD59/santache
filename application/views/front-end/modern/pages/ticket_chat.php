<main>
    <section class="container py-5">
        <div class="row">
            <div class="col-md-3 myaccount-navigation py-3">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div class="col-md-9 padding-16-30">
                <h2 class="section-title"><?= label('customer_support', 'Customer Support') ?></h2>
                <div class="bg-white p-3 border-radius-10">
                    <div class="border-bottom border-secondary-subtle">
                        <div class="py-4">
                            <div class="img-box-80 Executive-img m-auto shadow">
                                <img src="<?= base_url('assets/front_end/modern/image/icons/custamor executive.png') ?>" alt="" srcset="">
                            </div>
                            <h4 class="text-center fw-semibold my-2"><?= label('customer_executive', 'Customer Executive') ?></h4>
                        </div>
                        <h5 class="text-capitalize fw-semibold my-2">
                            <?php
                            $ticket_type = fetch_details('tickets', ['id' => $ticket_id], 'subject');
                            foreach ($ticket_type as $type) {

                                echo $type['subject'];
                            }
                            ?>
                        </h5>
                    </div>
                    <div class="py-3 msg-body" id='message_output'>
                        <div id="message-ul">
                            <?php
                            foreach ($ticket_chats['data'] as $chats) {
                                $session_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
                                $user_id = isset($chats['user_id']) ? $chats['user_id'] : '';
                                if ($session_user_id == $user_id) { ?>
                                    <div class="text-end py-2">
                                        <?php $attachment = isset($chats['attachments'][0]['media']) ? $chats['attachments'][0]['media'] : '';
                                        ?>
                                        <?php if (!empty($attachment)) { ?>
                                            <div class="reply-box">
                                                <div class="reply"><img class="chat-img" src=<?= $attachment ?>></div>
                                            </div>
                                            <p class="chat-send-time"><?php print_r($chats['last_updated']); ?></p>
                                        <?php } else { ?>
                                            <div class="reply-box">
                                                <div class="reply"><?= $chats['message'] ?></div>
                                            </div>
                                            <p class="chat-send-time"><?php print_r($chats['last_updated']); ?></p>
                                        <?php } ?>
                                    </div>
                                <?php  } else if ($session_user_id != $user_id) { ?>
                                    <div class="text-start py-2">
                                        <?php $attachment = isset($chats['attachments'][0]['media']) ? $chats['attachments'][0]['media'] : ''; ?>
                                        <?php if (!empty($attachment)) { ?>
                                            <div class="sender-box">
                                                <div class="sender"><img class="chat-img" src=<?= $attachment ?>></div>
                                            </div>
                                            <p class="chat-send-time"><?php print_r($chats['last_updated']); ?></p>
                                        <?php } else { ?>
                                            <div class="sender-box">
                                                <div class="sender"><?= $chats['message'] ?></div>
                                            </div>
                                            <p class="chat-send-time"><?php print_r($chats['last_updated']); ?></p>
                                        <?php } ?>
                                    </div>
                                <?php  } ?>
                            <?php } ?>
                        </div>
                    </div>
                    <div>
                        <form class="form-horizontal" id="ticket_send_msg_form" method="POST" enctype="multipart/form-data">
                            <div class="d-flex">
                                <input type="hidden" name="user_id" id="user_id" value="<?= $_SESSION['user_id'] ?>">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                                <input type="hidden" name="user_type" id="user_type" value="user">
                                <?php $last_updated = isset($chats['last_updated']) ? $chats['last_updated'] : '';
                                $session_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>
                                <input type="hidden" name="last_added" id="last_added" value="<?= $last_updated ?>">
                                <input type="hidden" name="ticket_id" id="ticket_id" value=<?= "$ticket_id" ?>>
                                <input type="text" class="form-control gray-700 ticket_msg" id="message_input" name="message" placeholder="Type here">
                                <input type="file" hidden class="form-control" id="upload" name="attachments[]" multiple>
                                <label class="message-send-icon" for="upload"><i class="ionicon-link-outline"></i></label>
                                <button type="submit" class="btn message-send-icon bg-primary" id="submit_btn"><i class="ionicon-paper-plane-outline"></i></button>
                            </div>
                            <div class="image-preview-container"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>