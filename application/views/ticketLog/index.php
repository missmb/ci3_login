<div class="row mt-3 mb-3">
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
        <form action="<?= base_url('ticket/searchlog'); ?>" method="post">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search Keyword" name="keywordlog" autofocus>
                <button class="btn btn-primary" type="submit" nama="submit">Search</button>
            </div>
        </form>
    </div>
    <div class="col-sm-3"></div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><?= $title ?> <small>Result : <?= $result ?></small></h6>
    </div>
    <?= $this->session->flashdata('message'); ?>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>User_Complain</th>
                        <th>Contact</th>
                        <th>Divisi</th>
                        <th>Place</th>
                        <th>Date Insert</th>
                        <th>Category</th>
                        <th>Detail</th>
                        <th>Status</th>
                        <th>Date Solve</th>
                        <th>Update Time</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>User_Complain</th>
                        <th>Contact</th>
                        <th>Divisi</th>
                        <th>Place</th>
                        <th>Date Insert</th>
                        <th>Category</th>
                        <th>Detail</th>
                        <th>Status</th>
                        <th>Date Solve</th>
                        <th>Update Time</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php $no = $this->uri->segment('3') + 1; ?>
                    <?php foreach ($data as $v) : ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><a href="<?= base_url('ticket/detaillog/' . $v['ID_TICKET_LOG']); ?>"><?= $v['ID_TICKET_LOG'] ?></a></td>
                            <td><a href="<?= base_url('ticket/detaillog/' . $v['ID_TICKET_LOG']); ?>"><?= $v['USER_COMPLAIN'] ?></a></td>
                            <td><?= $v['CONTACT'] ?></td>
                            <td><?= $v['DIVISI'] ?></td>
                            <td><?= $v['PLACE'] ?></td>
                            <td><?= $v['DATE_INSERTLOG'] ?></td>
                            <td><?= $v['CATEGORY'] ?></td>
                            <td><?= $v['DETAIL'] ?></td>
                            <td><?= $v['STATUS'] ?>
                            <td><?= $v['DATE_SOLVELOG'] ?>
                            <td><?= $v['UPDATE_TIMELOG'] ?></td>
                        </tr>
                        <?php $no++ ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="row">
                <div class="col">
                    <!--show pagination-->
                    <?php echo $pagination; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<!-- Modal Delete Ticket -->
<div class="modal fade" id="deleteTicket" tabindex="-1" role="dialog" aria-labelledby="deleteTicketLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTicketLabel">Add New Role</h5>
                <button type="submit" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('ticket/deletelog/' . $v['ID_TICKET_LOG']); ?>" method="POST">
                <div class="modal-body">
                    <h3>Are you Sure Want to Delete?</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>