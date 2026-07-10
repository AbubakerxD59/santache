<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?= label('customer_support', 'Customer Support') ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a>
                </li>
                <li class="breadcrumb-item"><a
                        href="#"><?= !empty($this->lang->line('customer_support')) ? $this->lang->line('customer_support') : 'Customer Support' ?></a>
                </li>
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

                <div class="col-md-12">
                    <h2 class="h6"><span
                            class="price"><?= !empty($this->lang->line('customer_support')) ? $this->lang->line('customer_support') : 'Customer Support' ?></span>
                    </h2>
                    <button type="submit" class="btn btn-info ticket_button"
                        value="Save"><?= labels('create_a_ticket', 'Create a ticket') ?></button>

                    <div class="display_fields col-md-12 d-none">
                        <form class="form-horizontal form-submit-event" id="ticket_form" method="POST"
                            enctype="multipart/form-data">

                            <select class="col-md-12 form-control mt-4" name="ticket_type_id" id="ticket_type">
                                <?php foreach ($ticket_types as $type) { ?>
                                    <option value="<?= $type['id'] ?>">
                                        <?= $type['title'] ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <input type="hidden" value="<?= $_SESSION['user_id'] ?>" name="user_id" id="user_id">

                            <input type="text" class="form-control mt-2" name="email" id="email"
                                placeholder="<?= !empty($this->lang->line('email')) ? $this->lang->line('email') : 'Email' ?>">

                            <input type="text" class="form-control mt-2" name="subject" id="subject"
                                placeholder="<?= !empty($this->lang->line('subject')) ? $this->lang->line('subject') : 'Subject' ?>">

                            <input type="text" class="form-control mt-2" name="description" id="description"
                                placeholder="<?= !empty($this->lang->line('description')) ? $this->lang->line('description') : 'Description' ?>">

                            <button type="submit" class="btn btn-info mt-4 ask_question">
                                <?= !empty($this->lang->line('save')) ? $this->lang->line('save') : 'Save' ?>
                            </button>
                        </form>
                    </div>




                    <?php
                    foreach ($tickets as $ticket) {
                        $ticket_type = fetch_details('ticket_types', ['id' => $ticket['ticket_type_id']], 'id,title');
                        $ticket_message = fetch_details('ticket_messages', ['ticket_id' => $ticket['id']], 'ticket_id');
                        $user_type = fetch_details('ticket_messages', ['ticket_id' => $ticket['id']], 'user_type');
                        $test = '';
                        foreach ($user_type as $type) {
                            if ($type['user_type'] != 'user') {
                                $test = ($type['user_type']);
                            }
                        }


                        $count = count($ticket_message);
                        ?>
                        <div class="price">

                            <?php
                            $rs = $this->db->query('select  last_updated from ticket_messages  where ticket_id =' . $ticket['id'] . ' order by last_updated desc');
                            $array = $rs->result_array();
                            if ($array[0] != '') {

                                $time = time2str($array[0]['last_updated']);
                            } else {
                                $time = '';
                            }
                            ?>

                            <table class="table mt-4 card ">
                                <tbody>
                                    <tr class="border-0">
                                        <th class='border-0 col-md-10 font-weight-bold' scope="row"
                                            class="font-weight-bold col-md-10"> <a href=<?= base_url('tickets/ticket_chat/' . $ticket['id']) ?>><?= $ticket['subject']; ?>
                                            </a>

                                        </th>
                                        <th class="border-0 text-end">
                                            <p><?= $time ?></p>
                                        </th>
                                    </tr>
                                    <tr class="border-0">

                                        <th class="border-0" scope="row"> <?= $ticket['description']; ?></th>
                                    </tr>

                                    <tr>
                                        <?php
                                        if ($ticket['status'] == '1') { ?>
                                            <th class="border-0 d-flex" scope="row"><span
                                                    class="badge badge-secondary">Pending</span>
                                                <span class=" ml-3"><?= $test ?> </span>
                                                <?php if (isset($count) && !empty($count)) { ?>
                                                    <a href='<?= base_url('tickets/ticket_chat/' . $ticket['id']) ?>'><i
                                                            class="fa fa-lg fa-comments ml-3"></i> <?= $count ?></a>
                                                <?php } ?>
                                                <span class="ml-3">
                                                    <?= date('d-m-Y', strtotime($ticket['date_created'])); ?>
                                                </span>
                                            </th>

                                        <?php } elseif ($ticket['status'] == '2') { ?>
                                            <th class="border-0 d-flex"><span class="badge badge-warning">Opened</span>
                                                <span class=" ml-3"><?= $test ?> </span>
                                                <?php if (isset($count) && !empty($count)) { ?>
                                                    <a href='<?= base_url('tickets/ticket_chat/' . $ticket['id']) ?>'> <i
                                                            class="fa fa-lg fa-comments ml-3 "></i> <?= $count ?></a>
                                                <?php } ?>
                                                <span class="ml-3">
                                                    <?= date('d-m-Y', strtotime($ticket['date_created'])); ?>
                                                </span>
                                            </th>

                                        <?php } elseif ($ticket['status'] == '3') { ?>
                                            <th class="border-0 d-flex" scope="row"><span
                                                    class="badge badge-primary">Resolved</span>
                                                <span class=" ml-3"><?= $test ?> </span>
                                                <?php if (isset($count) && !empty($count)) { ?>
                                                    <a href='<?= base_url('tickets/tickxet_chat/' . $ticket['id']) ?>'><i
                                                            class="fa fa-lg fa-comments ml-3"></i> <?= $count ?></a>
                                                <?php } ?>
                                                <span class="ml-3">
                                                    <?= date('d-m-Y', strtotime($ticket['date_created'])); ?>
                                                </span>
                                            </th>

                                        <?php } elseif ($ticket['status'] == '4') { ?>
                                            <th class="border-0 d-flex" scope="row"><span
                                                    class="badge badge-danger">Closed</span>
                                                <span class=" ml-3"><?= $test ?> </span>
                                                <?php if (isset($count) && !empty($count)) { ?>
                                                    <a href='<?= base_url('tickets/ticket_chat/' . $ticket['id']) ?>'><i
                                                            class="fa fa-lg fa-comments ml-3"></i> <?= $count ?></a>
                                                <?php } ?>
                                                <span class="ml-3">
                                                    <?= date('d-m-Y', strtotime($ticket['date_created'])); ?>
                                                </span>
                                            </th>

                                        <?php } else { ?>
                                            <th class="border-0 d-flex" scope="row"><span
                                                    class="badge badge-info">Reopened</span>
                                                <span class=" ml-3"><?= $test ?> </span>
                                                <?php if (isset($count) && !empty($count)) { ?>
                                                    <a href='<?= base_url('tickets/ticket_chat/' . $ticket['id']) ?>'> <i
                                                            class="fa fa-lg fa-comments ml-3"></i> <?= $count ?></a>
                                                <?php } ?>
                                                <span class="ml-3">
                                                    <?= date('d-m-Y', strtotime($ticket['date_created'])); ?>
                                                </span>
                                            </th>

                                        <?php } ?>

                                        <th class="border-0 text-end">
                                            <a href="javascript:void(0)" title="Edit" data-id="<?= $ticket['id'] ?>"
                                                data-toggle="modal" data-target="#<?= $ticket['id'] ?>"><i
                                                    class="fa fa-lg fa-pen ml-3"></i></a>
                                        </th>

                                    </tr>


                                </tbody>
                            </table>
                        </div>
                        <!-- Modals -->
                        <?php
                        $ticket_data = fetch_details('tickets', ['id' => $ticket['id']], '');
                        foreach ($ticket_data as $data) {
                            ?>
                            <!-- Ticket modal -->
                            <div class="modal" id="<?= $ticket['id'] ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <form class="form-horizontal form-submit-event card" id="stock_adjustment_form"
                                            method="POST" enctype="multipart/form-data"
                                            action="<?= base_url('tickets/update_ticket'); ?>">
                                            <div class="modal-header">
                                                <h4 class="modal-title price">Edit Ticket</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal">X</button>
                                            </div>
                                            <!-- Modal body -->

                                            <div>
                                                <label class="ml-4">Ticket type</label>
                                                <select class="col-md-10 form-control ml-4 mt-1 mb-3" name="ticket_type_id">
                                                    <?php foreach ($ticket_types as $ticket_type) { ?>

                                                        <option id='ticket_type' value="<?= $ticket_type['id'] ?>"
                                                            <?= (isset($data['ticket_type_id']) && $data['ticket_type_id'] == $ticket_type['id']) ? 'selected' : "" ?>>
                                                            <?= $ticket_type['title'] ?></option>
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
                                                    <input type="hidden" class="form-control " value=<?= $uname['username'] ?>
                                                        name="username" id="username">
                                                <?php } ?>

                                                <label class="ml-4">Email</label>
                                                <input type="text" class="form-control  col-md-10 ml-4 mt-1 mb-3"
                                                    placeholder="Email" name="email" value="<?= $data['email'] ?> "
                                                    id="email_id">

                                                <label class="ml-4">Subject</label>
                                                <input type="text" id="subject_id"
                                                    class="form-control  col-md-10 ml-4 mt-1 mb-3" placeholder="Subject"
                                                    name="subject" value="<?= $data['subject'] ?>">

                                                <label class="ml-4">Description</label>
                                                <input type="text" id="description_id"
                                                    class="form-control  col-md-10 ml-4 mt-1 mb-3" placeholder="Description"
                                                    name="description" value="<?= $data['description'] ?>">

                                                <input type="hidden" class="form-control " value=<?= $ticket['id'] ?>
                                                    name="edit_id" id="ticket_id">

                                                <button type="submit" class="btn btn-info mt-4 mb-3 ml-4"
                                                    value="Save"><?= labels('Update', 'Update') ?></button>

                                            </div>
                                        </form>
                                        <!-- Modal footer -->

                                    </div>
                                </div>
                            </div>
                        <?php }
                    } ?>
                </div>
                <nav class="text-center mt-4">
                    <?= (isset($links)) ? $links : '' ?>
                </nav>

            </div>

        </div>

    </div>

</section>