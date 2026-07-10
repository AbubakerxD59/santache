<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?= !empty($this->lang->line('compare')) ? $this->lang->line('compare') : 'Compare' ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item active"><?= !empty($this->lang->line('compare')) ? $this->lang->line('compare') : 'Compare' ?></li>
            </ol>
        </nav>
    </div>
</section>
<section class="main-content py-5 my-4">
    <div class="entry-content">
        <div id="compare-items">
            <div class="container">
                <div class="row">
                    <div class="col-8 d-flex justify-content-center h5"><?= label('compare_list_is_empty','Compare list is empty.')?></div>
                </div>
            </div>
        </div>
    </div>
</section>