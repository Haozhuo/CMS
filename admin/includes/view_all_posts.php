<?php
if(isset($_POST['checkBoxArray'])){
    global $connection;
    $bulk_option=$_POST['bulk_option'];
    //get the value of checkboxarray,i.e, post_id
    foreach($_POST['checkBoxArray'] as $post_id_value){
        switch($bulk_option){
            case 'published':
                //update post_status to public for checked posts
                $update_to_pub_query="UPDATE posts SET post_status='$bulk_option' WHERE post_id={$post_id_value}";
                $update_to_pub_result=mysqli_query($connection,$update_to_pub_query);
                confirm_query($update_to_pub_result);
                break;

            case 'draft':
                //update post_status to public for checked posts
                $update_to_draft_query="UPDATE posts SET post_status='$bulk_option' WHERE post_id={$post_id_value}";
                $update_to_draft_result=mysqli_query($connection,$update_to_draft_query);
                confirm_query($update_to_draft_result);
                break;                

            case 'delete':
                $delete_query="DELETE FROM posts WHERE post_id={$post_id_value}";
                $delete_result=mysqli_query($connection,$delete_query);
                confirm_query($delete_result);
                break;

            default:
                break;
        }
    }

}





?>


<form action="" method="post">
<table class="table table-bordered table-hover">
    
    <div id="bulkOptionContainer" class="col-xs-4">
        <select name="bulk_option" id="" class="form-control">
            <option value="">Select Options</option>
            <option value="published">Publish</option>
            <option value="draft">Draft</option>
            <option value="delete">Delete</option>
        </select>
    </div>

        <div class="col-xs-4">
            <input type="submit" name="submit" class="btn btn-success" value="Apply">
            <a class="btn btn-primary" href="posts.php?source=add_post">Add New</a>
        </div>
                            <thead>
                                <tr>
                                    <th><input id="selectAllBoxes" type="checkbox"></th>
                                    <th>Id</th>
                                    <th>Author</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Image</th>
                                    <th>Tags</th>
                                    <th>Comments</th>
                                    <th>Date</th>
                                    <th>View Post</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                 </tr>   
                            </thead>

                            <tbody>
                            <?php
                                global $connection;
                                $select_all_query="SELECT * FROM posts";
                                $select_posts=mysqli_query($connection,$select_all_query);

                                while($row=mysqli_fetch_assoc($select_posts)){
                                    //cahe results
                                    $post_id=$row['post_id'];
                                    $post_author=$row['post_author'];
                                    $post_title=$row['post_title'];
                                    $post_category_id=$row['post_category_id'];
                                    $post_status=$row['post_status'];
                                    $post_image=$row['post_image'];
                                    $post_tags=$row['post_tags'];
                                    $post_comment_counts=$row['post_comment_counts'];
                                    $post_date=$row['post_date'];
                                    //consruct the table
                                    echo "<tr>";
                            ?>

                            <td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='<?php echo $post_id; ?>'></td>

                            <?php
                                    echo "<td>{$post_id}</td>";
                                    echo "<td>{$post_author}</td>";
                                    echo "<td>{$post_title}</td>";
                                    //get the category title from categories database by using
                                    //cpost_category_id in posts table
                                    //limit the selectin to 1
                                    $find_category_id_query="SELECT * FROM categories WHERE cat_id={$post_category_id} LIMIT 1";
                                    $find_category_id_result=mysqli_query($connection,$find_category_id_query);

                                    confirm_query($find_category_id_result);

                                    while($row=mysqli_fetch_assoc($find_category_id_result)){
                                        //print_r($find_category_id_result);
                                        $cat_id=$row['cat_id'];
                                        $cat_title=$row['cat_title'];

                                        echo "<td>{$cat_title}</td>";
                                    }

                                    echo "<td>{$post_status}</td>";
                                    //get the image from server
                                    echo "<td><img width='100' src='../images/{$post_image}' alt='image'></td>";
                                    echo "<td>{$post_tags}</td>";
                                    echo "<td>{$post_comment_counts}</td>";
                                    echo "<td>{$post_date}</td>";

                                /*
                                    //construct an associative array
                                    $post['post_id']=$row['post_id'];
                                    $post['post_author']=$row['post_author'];
                                    $post['post_title']=$row['post_title'];
                                    $post['post_category_id']=$row['post_category_id'];
                                    $post['post_status']=$row['post_status'];
                                    $post['post_image']=$row['post_image'];
                                    $post['post_tags']=$row['post_tags'];
                                    $post['post_comment_counts']=$row['post_comment_counts'];
                                    $post['post_date']=$row['post_date'];

                                    $post_id=$post['post_id'];

                                    $find_category_id_query="SELECT * FROM categories WHERE cat_id={$post['post_category_id']} LIMIT 1";
                                    $find_category_id_result=mysqli_query($connection,$find_category_id_result);

                                    confirm_query($find_category_id_result);

                                    while($row=mysqli_fetch_assoc($find_category_id_result)){
                                        $cat_id=$row['cat_id'];
                                        $cat_title=$row['cat_title'];
                                    }

                                    $post['category_title']=$cat_title;

                                    echo "<tr>";

                                    //build the table by iterating the array
                                    foreach($post as $key=>$value){
                                        if($key != 'post_image'){
                                            echo "<td>{$value}</td>";
                                        }else{
                                            echo "<td><img width='100' src='../images/{$value}' alt='image'></td>";
                                        }
                                    }
                                */
                                    //link to view specific post
                                    echo "<td><a href='../post.php?p_id={$post_id}'>View Post</a></td>";
                                    //use source to identify behavior and p_id to give which element to edit
                                    echo "<td><a href='posts.php?source=edit_post&p_id={$post_id}'>Edit</a></td>";
                                    echo "<td><a id='con' href='posts.php?delete={$post_id}'>Delete</a></td>";
                                    echo "</tr>";
                                }
                            ?>
                            </tbody>
                                
                        </table>
                    </form>

<?php
//delete post by its id
if(isset($_GET['delete'])){
    global $connection;
    $delete_id=$_GET['delete'];

    $delete_query="DELETE FROM posts WHERE post_id={$delete_id}";
    $delete_result=mysqli_query($connection,$delete_query);

    confirm_query($delete_result);

    header("Location: posts.php");
}

?>