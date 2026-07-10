<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?=label('customer_support','Customer Support')?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('customer_support')) ? $this->lang->line('customer_support') : 'Customer Support' ?></a></li>
            </ol>
        </nav>
    </div>

</section>
<section class="home_customer_support_sec mt-5" id="customer_support">
    <div class="main-content">
        <div class="row">
            <div class="col-md-4">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div class="home_faq col-md-7">

                <div class="col-12">
                    <div class=" chat-area modal-dialog-scrollable mt-3">
                        <!-- chatbox -->
                        <div class="page-header">
                            <h4 class="h3 ml-3">
                                <?php
                                $ticket_type = fetch_details('tickets', ['id' => $ticket_id], 'subject');
                                foreach ($ticket_type as $type) {

                                    echo $type['subject'];
                                }
                                ?>

                            </h4>

                            <div class="modal-content bg-white">


                                <div class="msg-body" id='message_output'>
                                    <ul id="message-ul">
                                        <?php
                                        foreach ($ticket_chats['data'] as $chats) {
                                            $session_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
                                            $user_id = isset($chats['user_id']) ? $chats['user_id'] : '';
                                            if ($session_user_id == $user_id) { ?>
                                                <li class="repaly">

                                                    <?php $attachment = isset($chats['attachments'][0]['media']) ? $chats['attachments'][0]['media'] : '';

                                                    ?>

                                                    <?php if (!empty($attachment)) { ?>
                                                        <p>
                                                            <img class="chat-img" src=<?= $attachment ?>>
                                                        </p><br>
                                                        <span class="price"><?php print_r($chats['last_updated']); ?></span>
                                                    <?php } else { ?>
                                                        <p><?= $chats['message']  ?> </p><br>

                                                        <span class="price"><?php print_r($chats['last_updated']); ?></span>
                                                    <?php } ?>
                                                </li>
                                            <?php  } else if ($session_user_id != $user_id) { ?>
                                                <li class="sender price">
                                                    <?php $attachment = isset($chats['attachments'][0]['media']) ? $chats['attachments'][0]['media'] : ''; ?>
                                                    <?php if (!empty($attachment)) { ?>
                                                        <p>
                                                            <img class="chat-img" src=<?= $attachment ?>>
                                                        </p><br>
                                                        <span class="price"><?php print_r($chats['last_updated']); ?></span>
                                                    <?php } else { ?>
                                                        <p x><?= $chats['message']  ?> </p><br>

                                                        <span class="price"><?php print_r($chats['last_updated']); ?></span>
                                                    <?php } ?>
                                                </li>
                                            <?php  } ?>

                                        <?php } ?>
                                    </ul>

                                </div>

                                
                                <!-- </div> -->

                            </div>

                            <div class="send-box card-body mb-3">
                                <form class="form-horizontal" id="ticket_send_msg_form" method="POST" enctype="multipart/form-data">
                                    <div class="input-group">
                                        <input type="hidden" name="user_id" id="user_id" value="<?= $_SESSION['user_id'] ?>">
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                                        <input type="hidden" name="user_type" id="user_type" value="user">
                                        <?php $last_updated = isset($chats['last_updated']) ? $chats['last_updated'] : '';
                                        $session_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>
                                        <input type="hidden" name="last_added" id="last_added" value="<?= $last_updated ?>">
                                        <input type="hidden" name="ticket_id" id="ticket_id" value=<?= "$ticket_id" ?>>
                                        <input type="text" name="message" class="col-md-12 mr-1 ticket_msg form-control" id="message_input" placeholder="Type Message ...">
                                        <input type="file" hidden class="form-control" id="upload" name="attachments[]" multiple>
                                        <label class=" btn btn-primary mr-1" for="upload"><i class="fa fa-paperclip"></i></label>
                                        <button type="submit" class="btn btn-primary col-md-1" id="submit_btn">Send</button>

                                    </div>
                                </form>
                            </div>
                          

                        </div>
                    </div>

                </div>
            </div>

            <!-- </div> -->


        </div>

    </div>

    </div>

</section>