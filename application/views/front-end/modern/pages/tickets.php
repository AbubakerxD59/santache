<main>
    <section class="container py-5">
        <div class="row">
            <div class="col-md-3 myaccount-navigation py-3">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div class="col-md-9 padding-16-30 home_faq">
                <div class="align-items-center d-flex flex-wrap justify-content-between pb-3">
                    <h2 class="section-tile"><span
                            class="price"><?= !empty($this->lang->line('customer_support')) ? $this->lang->line('customer_support') : 'Customer Support' ?></span>
                    </h2>
                    <button type="submit" class="btn viewmorebtn ticket_button"
                        value="Save"><?= labels('Create a ticket', 'Create a ticket') ?></button>
                </div>
                <div class="display_fields col-md-12 d-none">
                    <form class="form-horizontal" id="create_ticket_form" method="POST" enctype="multipart/form-data">
                        <select class="col-md-12 form-control" name="ticket_type_id" id="ticket_type" required>
                            <option value="" selected>Select Ticket Type</option>
                            <?php foreach ($ticket_types as $type) {
                                if (isset($product_details[0]['tax']) && $product_details[0]['tax'] == $type['id']) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }
                                ?>
                                <option value="<?= $type['id'] ?>" <?= $selected ?>><?= $type['title'] ?></option>
                                <?php
                            } ?>
                        </select>

                        <input type="hidden" class="form-control mt-2" value="<?= $_SESSION['user_id'] ?>"
                            name="user_id" id="user_id">
                        <input type="email" class="form-control mt-2" placeholder="Email" name="email" id="email">
                        <input type="text" class="form-control mt-2" placeholder="Subject" name="subject" id="subject"
                            required>
                        <textarea name="description" id="description" class="form-control mt-2"
                            placeholder="Description" cols="30" rows="3" required></textarea>

                        <button type="submit" class="btn btn-primary mt-2"><?= labels('Send', 'Send') ?></button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table ticket-table">
                        <thead class="border-0">
                            <tr class="border-0">
                                <th scope="col" class="bg-light border-0 text-body-secondary"><?= label('id', 'Id') ?>
                                </th>
                                <th scope="col" class="bg-light border-0 text-body-secondary">
                                    <?= label('ticket', 'Ticket') ?>
                                </th>
                                <th scope="col" class="bg-light border-0 text-body-secondary">
                                    <?= label('status', 'Status') ?>
                                </th>
                                <th scope="col" class="bg-light border-0 text-body-secondary">
                                    <?= label('assignee', 'Assignee') ?>
                                </th>
                                <th scope="col" class="bg-light border-0 text-body-secondary">
                                    <?= label('create_date', 'Create Date') ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="border-0">
                            <?php
                            foreach ($tickets as $ticket) {

                                $ticket_type = fetch_details('ticket_types', ['id' => $ticket['ticket_type_id']], 'id,title');
                                $ticket_message = fetch_details('ticket_messages', ['ticket_id' => $ticket['id']], 'ticket_id');
                                $user_type = fetch_details('ticket_messages', ['ticket_id' => $ticket['id']], 'user_type');
                                $assignee = '';
                                foreach ($user_type as $type) {
                                    if ($type['user_type'] != 'user') {
                                        $assignee = ($type['user_type']);
                                    }
                                }
                                $count = count($ticket_message);
                                ?>
                                <tr class="border-0 border-radius-10">
                                    <th scope="row" class="border-0 border-radius-10 ticket-chat-link"
                                        data-href='<?= base_url('tickets/ticket_chat/' . $ticket['id']) ?>'>
                                        #<?= $ticket['id'] ?></th>
                                    <td class="border-0 text-start ticket-chat-link"
                                        data-href='<?= base_url('tickets/ticket_chat/' . $ticket['id']) ?>'>
                                        <p class="m-0 fw-semibold" title="<?= $ticket['subject'] ?>">
                                            <?= word_limit($ticket['subject'], 60); ?>
                                        </p>
                                        <p class="m-0 text-body-secondary"><?= $ticket['description']; ?></p>
                                    </td>
                                    <td class="border-0 ticket-chat-link"
                                        data-href='<?= base_url('tickets/ticket_chat/' . $ticket['id']) ?>'>
                                        <?php
                                        if ($ticket['status'] == '1') { ?>
                                            <span class="d-flex align-items-center gap-1 text-warning fw-semibold"><ion-icon
                                                    name="disc-sharp"></ion-icon> <?= label('pending', 'Pending') ?></span>

                                        <?php } elseif ($ticket['status'] == '2') { ?>
                                            <span class="d-flex align-items-center gap-1 text-success fw-semibold"><ion-icon
                                                    name="disc-sharp"></ion-icon> <?= label('opened', 'Opened') ?></span>

                                        <?php } elseif ($ticket['status'] == '3') { ?>
                                            <span class="d-flex align-items-center gap-1 text-info fw-semibold"><ion-icon
                                                    name="disc-sharp"></ion-icon> <?= label('resolved', 'Resolved') ?></span>

                                        <?php } elseif ($ticket['status'] == '4') { ?>
                                            <span class="d-flex align-items-center gap-1 text-danger fw-semibold"><ion-icon
                                                    name="disc-sharp"></ion-icon> <?= label('closed', 'Closed') ?></span>

                                        <?php } else { ?>
                                            <span class="d-flex align-items-center gap-1 fw-semibold text-success"><ion-icon
                                                    name="disc-sharp"></ion-icon> <?= label('reopened', 'Reopened') ?></span>

                                        <?php } ?>
                                    </td>
                                    <td class="border-0 fw-semibold ticket-chat-link"
                                        data-href='<?= base_url('tickets/ticket_chat/' . $ticket['id']) ?>'><?= $assignee ?>
                                    </td>
                                    <td class="border-0 fw-semibold ticket-chat-link"
                                        data-href='<?= base_url('tickets/ticket_chat/' . $ticket['id']) ?>'>
                                        <?= date('d M Y', strtotime($ticket['date_created'])) ?>
                                    </td>
                                    <td class="border-0 fw-semibold border-radius-10"><a data-href="#"
                                            data-bs-toggle="modal" data-bs-target="#<?= $ticket['id'] ?>"><ion-icon
                                                name="pencil-outline" class="fs-5"></ion-icon></a></td>
                                </tr>
                                <?php
                                $ticket_data = fetch_details('tickets', ['id' => $ticket['id']], '');
                                foreach ($ticket_data as $data) {
                                    ?>

                                    <div class="modal fade" id="<?= $ticket['id'] ?>" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="section-title" id="exampleModalLabel">
                                                        <?= label('edit_ticket', 'Edit ticket') ?>
                                                    </h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="form-horizontal form-submit-event" id="" method="POST"
                                                        enctype="multipart/form-data"
                                                        action="<?= base_url('tickets/update_ticket'); ?>">
                                                        <div class="card">
                                                            <label
                                                                class="ml-4"><?= label('ticket_type', 'Ticket type') ?></label>
                                                            <select class="col-md-10 form-control ml-4 mt-1 mb-3"
                                                                name="ticket_type_id">
                                                                <?php foreach ($ticket_types as $ticket_type) { ?>

                                                                    <option id='ticket_type' value="<?= $ticket_type['id'] ?>"
                                                                        <?= (isset($data['ticket_type_id']) && $data['ticket_type_id'] == $ticket_type['id']) ? 'selected' : "" ?>><?= $ticket_type['title'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <input id="user_id" type="hidden" class="form-control"
                                                                value=<?= $_SESSION['user_id'] ?> name="user_id">
                                                            <?php
                                                            $user_name = fetch_details('users', ['id' => $_SESSION['user_id']], 'username');
                                                            foreach ($user_name as $uname) {
                                                                ?>

                                                                <input id="user_id" type="hidden" class="form-control"
                                                                    value=<?= $_SESSION['user_id'] ?> name="user_id">
                                                                <input type="hidden" class="form-control "
                                                                    value=<?= $uname['username'] ?> name="username" id="username">
                                                            <?php } ?>

                                                            <label class="ml-4"><?= label('email', 'Email') ?></label>
                                                            <input type="text" class="form-control  col-md-10 ml-4 mt-1 mb-3"
                                                                placeholder="Email" name="email" value="<?= $data['email'] ?> "
                                                                id="email_id">

                                                            <label class="ml-4"><?= label('subject', 'Subject') ?></label>
                                                            <!-- <input type="text" id="subject_id"
                                                                class="form-control  col-md-10 ml-4 mt-1 mb-3"
                                                                placeholder="Subject" name="subject" value=""> -->
                                                            <textarea name="subject" id="subject_id"
                                                                class="form-control  col-md-10 ml-4 mt-1 mb-3"><?= $data['subject'] ?></textarea>

                                                            <label
                                                                class="ml-4"><?= label('description', 'Description') ?></label>
                                                            <input type="text" id="description_id"
                                                                class="form-control  col-md-10 ml-4 mt-1 mb-3"
                                                                placeholder="Description" name="description"
                                                                value="<?= $data['description'] ?>">

                                                            <input type="hidden" class="form-control " value=<?= $ticket['id'] ?>
                                                                name="edit_id" id="ticket_id">
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="submit"
                                                                class="btn btn-primary"><?= label('update', 'Update') ?></button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                            } ?>
                        </tbody>
                    </table>
                </div>
                <?php
                if (empty($tickets)) { ?>
                    <div class="container mt-5">
                        <div class="card text-center border-danger">
                            <div class="card-body">
                                <h5 class="card-title text-danger">NO TICKETS FOUND</h5>
                                <p class="card-text">There are currently no tickets available in the system.</p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <nav class="text-center mt-3">
                    <?= (isset($links)) ? $links : '' ?>
                </nav>
            </div>
        </div>
    </section>
</main>