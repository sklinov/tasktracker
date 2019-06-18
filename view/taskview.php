<?php
class TaskView {
    
    public  $is_admin;
    public  $current_page;
    public  $sort_by;
    public  $sort_order_asc;
    private $tasks_array = [];
    private $number_of_pages;
    private $number_of_tasks_on_page = 3;
    private $admin_login_btn_label;
    private $admin_login_btn_class;
    private $admin_login_btn_id;
    

    public function showTasks($tasks_array) {
        
        $this->is_admin = filter_var($_SESSION['is_admin'], FILTER_VALIDATE_BOOLEAN);
        if($this->is_admin) {
            $this->admin_login_btn_label = "Administrator logged in";
            $this->admin_login_btn_class = "btn btn-warning";
            $this->prepareEditTaskModal();
        }
        else {
            $this->admin_login_btn_label = "Administartor login";
            $this->admin_login_btn_class = "btn btn-info";
        }
        
        $this->tasks_array = $tasks_array;
        $this->sortTasks();
        $this->prepareAdminLoginModal();
        $this->showControls();
        $this->prepareTaskModal();
        
        $this->tasksToShow();
        $this->showPagination();
    }
    
    private function tasksToShow() {
        
        $offset = ($this->current_page-1) * $this->number_of_tasks_on_page;
        $tasks_array = array_slice($this->tasks_array,
                                   $offset,
                                   $this->number_of_tasks_on_page,
                                   $preserve_keys = TRUE);
        echo '<div class="row">';
        foreach($tasks_array as $task) {
            $cheked = $task['completed'] != 0 ? 'checked' : '';
            echo '
            <div class="card m-3" style="width: 18rem;" id="task-"'.$task['id'].'>
                <div class="card-header">
                <strong id="username">'.$task['username'].'</strong> <br> <span id="email">'.$task['email'].'</span>
                </div>
            <div class="card-body">
              <p class="card-text">'.$task['text'].'</p>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" '.$cheked.' disabled>
                <label class="form-check-label" for="defaultCheck1">
                    Completed
                </label>
              </div>';
                
              if($this->is_admin) {
                echo '<button type="button" 
                      class="btn btn-danger mt-3" 
                      data-toggle="modal" 
                      data-target="#edit-task-modal" 
                      data-id="'.$task['id'].'"
                      data-username="'.$task['username'].'"
                      data-email="'.$task['email'].'"
                      data-text="'.$task['text'].'"
                      data-completed="'.$task['completed'].'" 
                      id="task-edit-btn">Edit</button>';
                }
           echo '
            </div>
            </div>
            ';
        }
        echo '</div>';
    }

    private function showControls() {
        echo '
            <div class="row">
                <div class="btn-toolbar ml-3" role="toolbar" aria-label="Sort and add controls">
                    <label for="sortby" class="text mr-2 mt-2">Sort by:</label>
                    <div class="btn-group mr-2" role="group" aria-label="Sort by" name="sortby">
                        <button type="button" class="btn btn-secondary" id="sort-by-btn" data-sortby="username">Username</button>
                        <button type="button" class="btn btn-secondary" id="sort-by-btn" data-sortby="email">E-mail</button>
                        <button type="button" class="btn btn-secondary" id="sort-by-btn" data-sortby="completed">Status</button>
                    </div>
                    <div class="btn-group mr-2" role="group" aria-label="Add a task">
                        <button type="button" class="btn btn-primary" id="add-task-btn" data-toggle="modal" data-target="#add-task-modal">Add a task</button>
                    </div>
                    <div class="btn-group mr-2" role="group" aria-label="Administrator login">
                        <button type="button" class="'.$this->admin_login_btn_class.'" data-toggle="modal" data-target="#admin-login-modal">'.$this->admin_login_btn_label.'</button>
                        <button type="button" class="btn btn-secondary" data-toggle="modal" id="admin-logout-btn" >Logout</button>
                    </div>
                </div>
            </div>
        '; 
    }

    private function prepareTaskModal() { 
        echo '
        <div class="modal fade" id="add-task-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add a task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" placeholder="Enter your username">
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" placeholder="name@example.com">
                        </div>
                        <div class="form-group">
                            <label for="task-text">Task</label>
                            <textarea class="form-control" id="task-text" placeholder="Task contents..."></textarea>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="add-task-btn-submit" data-dismiss="modal">Add a task</button>
                    </form>
                </div>
                <div class="modal-footer">
                
                </div>
            </div>
            </div>
        </div>
        ';
    }
    
    private function prepareEditTaskModal() { 
        echo '
        <div class="modal fade" id="edit-task-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit the task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" id="id-edit"></input>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username-edit" placeholder="Enter your username">
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email-edit" placeholder="name@example.com">
                        </div>
                        <div class="form-group">
                            <label for="task-text">Task</label>
                            <textarea class="form-control" id="task-text-edit" placeholder="Task contents..."></textarea>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="completed-edit">
                            <label class="form-check-label" for="completed-edit">
                                Completed
                            </label>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger" id="edit-task-btn-submit" data-dismiss="modal">Edit a task</button>
                    </form>
                </div>
                <div class="modal-footer">
                
                   
                </div>
            </div>
            </div>
        </div>
        ';
    }

    private function prepareAdminLoginModal() {
        echo '
        <div class="modal fade" id="admin-login-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Administrator login</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="admin-username">Username</label>
                            <input type="text" class="form-control" id="admin-username" placeholder="Enter your username">
                        </div>
                        <div class="form-group">
                            <label for="admin-password">Password</label>
                            <input type="password" class="form-control" id="admin-password">
                        </div>
                   
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="admin-login-btn">Login</button>
                </form>
                </div>
            </div>
            </div>
        </div>
        ';
    }

    private function showPagination() {
        $number_of_tasks  = count($this->tasks_array);
        $this->number_of_pages = ceil($number_of_tasks/3);
        echo '
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item"><button class="page-link" id="page-prev">Previous</button></li>';
                for($page_number=1;$page_number<=$this->number_of_pages;$page_number++)
                {
                    echo '<li class="page-item"><button class="page-link" id="page-link" data-page="'.$page_number.'">'.$page_number.'</button></li>';
                }
           echo '<li class="page-item"><button class="page-link" id="page-next">Next</button></li>
            </ul>
        </nav>
        
        ';
    }

    private function sortTasks() {
        if($this->sort_order_asc == "true")
        {
            usort($this->tasks_array, function ($task1, $task2) {
                return $task1[$this->sort_by] <=> $task2[$this->sort_by];
            });
        } else {
            usort($this->tasks_array, function ($task1, $task2) {
                return $task2[$this->sort_by] <=> $task1[$this->sort_by];
            }); 
        }

    }



}