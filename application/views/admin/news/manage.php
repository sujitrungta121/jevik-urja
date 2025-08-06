<?php $this->load->view('admin/header'); ?>
<div id="page-wrapper">
    <div class="container-fluid">
        <h1 class="page-header">
            News Management <small>// Add or Update Today's News</small>
        </h1>
        
        <div class="row">
            <div class="col-lg-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php if($news_data): ?>
                            <h3 class="panel-title">Update News</h3>
                        <?php else: ?>
                            <h3 class="panel-title">Add News</h3>
                        <?php endif; ?>
                    </div>
                    <div class="panel-body">
                        <?php echo form_open('admin/news', array('class' => 'form-horizontal', 'role' => 'form')); ?>
                        
                        <div class="form-group">
                            <label for="news_text" class="col-sm-2 control-label">News Text</label>
                            <div class="col-sm-10">
                                    <textarea style="width: 100%;" class="form-control no-editor" name="news_text"  rows="6" id="news_text" required><?php echo $news_data ? $news_data->news_text : ''; ?></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="news_date" class="col-sm-2 control-label">News Date</label>
                            <div class="col-sm-10">
                                <input 
                                    type="date" 
                                    name="news_date" 
                                    id="news_date" 
                                    class="form-control" 
                                    value="<?php echo $news_data ? date('Y-m-d', strtotime($news_data->news_date)) : date('Y-m-d'); ?>"
                                    required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">
                                    <?php if($news_data): ?>
                                        <i class="fa fa-edit"></i> Update News
                                    <?php else: ?>
                                        <i class="fa fa-plus"></i> Add News
                                    <?php endif; ?>
                                </button>
                                
                                <?php if($news_data): ?>
                                    <a href="<?php echo base_url('admin/news/delete'); ?>" 
                                       class="btn btn-danger" 
                                       onclick="return confirm('Are you sure you want to delete this news?');">
                                        <i class="fa fa-trash"></i> Delete News
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Current News</h3>
                    </div>
                    <div class="panel-body">
                        <?php if($news_data): ?>
                            <h4>Date: <?php echo date('d/m/Y', strtotime($news_data->news_date)); ?></h4>
                            <hr>
                            <div style="word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; white-space: pre-wrap; max-width: 100%;">
                                <?php echo $news_data->news_text; ?>
                            </div>
                            <hr>
                            <small class="text-muted">
                                Last Updated: <?php echo date('d/m/Y H:i:s', strtotime($news_data->updated_at)); ?>
                            </small>
                        <?php else: ?>
                            <p class="text-muted">No news available. Please add some news.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('admin/footer'); ?>
