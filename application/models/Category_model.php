<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Category_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }
    public function get_categories($id = NULL, $limit = '', $offset = '', $sort = 'row_order', $order = 'ASC', $has_child_or_item = 'true', $slug = '', $ignore_status = '')
    {
        $level = 0;
        if ($ignore_status == 1) {
            $where = (isset($id) && !empty($id)) ? ['c1.id' => $id] : ['c1.parent_id' => 0, 'c1.id !=' => 1];
        } else {
            $where = (isset($id) && !empty($id)) ? ['c1.id' => $id, 'c1.status' => 1] : ['c1.parent_id' => 0, 'c1.status' => 1];
        }

        $this->db->select('c1.*');
        $this->db->where($where);
        if (!empty($slug)) {
            $this->db->where('c1.slug', $slug);
        }
        if ($has_child_or_item == 'false') {
            $this->db->join('categories c2', 'c2.parent_id = c1.id', 'left');
            $this->db->join('products p', ' p.category_id = c1.id', 'left');
            $this->db->group_start();
            $this->db->or_where(['c1.id ' => ' p.category_id ', ' c2.parent_id ' => ' c1.id '], NULL, FALSE);
            $this->db->group_End();
            $this->db->group_by('c1.id');
        }

        if (!empty($limit) || !empty($offset)) {
            $this->db->offset($offset);
            $this->db->limit($limit);
        }

        $this->db->order_by($sort ?? '', $order ?? '');

        $parent = $this->db->get('categories c1');
        $categories = $parent->result();
        $count_res = $this->db->count_all_results('categories c1');
        $i = 0;


        foreach ($categories as $p_cat) {
            $categories[$i]->children = $this->sub_categories($p_cat->id, $level);
            $categories[$i]->text = output_escaping(str_replace('\r\n', '&#13;&#10;', $p_cat->name));
            $categories[$i]->name =  output_escaping(str_replace('\r\n', '&#13;&#10;', $categories[$i]->name));
            $categories[$i]->state = ['opened' => true];
            $categories[$i]->icon = "jstree-folder";
            $categories[$i]->level = $level;
            $categories[$i]->image = get_image_url($categories[$i]->image, 'thumb', 'sm');
            $categories[$i]->banner = get_image_url($categories[$i]->banner, 'thumb', 'md');
            $i++;
        }
        if (isset($categories[0])) {
            $categories[0]->total = $count_res;
        }
        return  json_decode(json_encode($categories), 1);
    }

    public function sub_categories($id, $level)
    {
        $level = $level + 1;
        $this->db->select('c1.*');
        $this->db->from('categories c1');
        $this->db->where(['c1.parent_id' => $id, 'c1.status' => 1]);
        $child = $this->db->get();
        $categories = $child->result();
        $i = 0;
        foreach ($categories as $p_cat) {

            $categories[$i]->children = $this->sub_categories($p_cat->id, $level);
            $categories[$i]->text = output_escaping(str_replace('\r\n', '&#13;&#10;', $p_cat->name));
            $categories[$i]->name = output_escaping(str_replace('\r\n', '&#13;&#10;', $p_cat->name));
            $categories[$i]->state = ['opened' => true];
            $categories[$i]->level = $level;
            $categories[$i]->image = get_image_url($categories[$i]->image, 'thumb', 'md');
            $categories[$i]->banner = get_image_url($categories[$i]->banner, 'thumb', 'md');
            $i++;
        }
        return $categories;
    }

    public function get_categories_for_dropdown($parent_id = 0, $prefix = '')
    {
        $this->db->select('id, name, parent_id');
        $this->db->where(['parent_id' => $parent_id, 'status' => 1]);
        $this->db->order_by('name', 'ASC');
        $categories = $this->db->get('categories')->result_array();
        
        $result = [];
        foreach ($categories as $category) {
            $category['name'] = $prefix . output_escaping($category['name']);
            $result[] = $category;
            $subcategories = $this->get_categories_for_dropdown($category['id'], $prefix . '&nbsp;&nbsp;&nbsp;&nbsp;');
            if (!empty($subcategories)) {
                $result = array_merge($result, $subcategories);
            }
        }
        return $result;
    }

    public function delete_category($id)
    {
        // Escape the ID to prevent SQL injection
        $id = escape_array($id);
    
        // Check if the category is assigned to any products
        $query = $this->db->where('category_id', $id)->get('products');
        if ($query->num_rows() > 0) {
            // If there are products associated with this category, return FALSE
            return FALSE;
        }    
        if ($this->check_subcategories_have_products($id)) {
            return FALSE;
        }
    
        // If no products are associated, proceed with the deletion
        $this->db->trans_start();
        $this->db->where('id', $id)->delete('categories');
        $this->db->trans_complete();
    
        // Return the transaction status
        return $this->db->trans_status();
    }

    private function check_subcategories_have_products($category_id)
    {
        $subcategories = $this->db->where('parent_id', $category_id)->get('categories')->result_array();
        
        foreach ($subcategories as $subcategory) {
            $query = $this->db->where('category_id', $subcategory['id'])->get('products');
            if ($query->num_rows() > 0) {
                return TRUE;
            }            
            if ($this->check_subcategories_have_products($subcategory['id'])) {
                return TRUE;
            }
        }
        return FALSE;
    }
    public function check_subcategories_have_products_public($category_id)
    {
        return $this->check_subcategories_have_products($category_id);
    }


    public function get_category_list()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $multipleWhere = '';
        $where = ['status !=' => NULL];

        if (isset($_GET['id']))
            $where['parent_id'] = $_GET['id'];
        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = ['`id`' => $search, '`name`' => $search];
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }
        $cat_count = $count_res->get('categories')->result_array();

        foreach ($cat_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' * ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $cat_search_res = $search_res->order_by($sort, "desc")->limit($limit, $offset)->get('categories')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();


        foreach ($cat_search_res as $row) {



            $operate = '<div class="dropdown">
            <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
              <a class="dropdown-item" href=' . base_url('admin/category/create_category') . '?edit_id=' . $row['id'] . '><i class="fa fa-pen"></i> Edit</a>
              <a href="javascript:void(0)" class="delete-categoty dropdown-item" data-id=' . $row['id'] . ' title="Delete" ><i class="fa fa-trash"></i> Delete</a></div>';

            $tempRow['id'] = $row['id'];

            $tempRow['name'] = output_escaping($row['name']);
            if ($row['status'] == '1') {
                $tempRow['status'] = '<a class="badge bg-success text-white" ></a>';
                $tempRow['status'] .= '<a class="form-switch update_active_status " data-table="categories" title="Deactivate" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><input class="form-check-input " type="checkbox" role="switch" checked></a>';
            } else {
                $tempRow['status'] = '<a class="badge bg-danger text-white" ></a>';
                $tempRow['status'] .= '<a class="form-switch update_active_status mr-1 mb-1" data-table="categories" title="Deactivate" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><input class="form-check-input " type="checkbox" role="switch" ></a>';
            }
            if (empty($row['image']) || file_exists(FCPATH  . $row['image']) == FALSE) {
                $row['image'] = base_url() . NO_IMAGE;
                $row['image_main'] = base_url() . NO_IMAGE;
            } else {
                $row['image_main'] = base_url($row['image']);
                $row['image'] = get_image_url($row['image'], 'thumb', 'sm');
            }
            $tempRow['image'] = "<div class='image-box-100'><a href='" . $row['image_main'] . "' data-toggle='lightbox' data-gallery='gallery'> <img src='" . $row['image'] . "' class='h-25 w-75' ></a></div>";

            if (empty($row['banner']) || file_exists(FCPATH  . $row['banner']) == FALSE) {
                $row['banner'] = base_url() . NO_IMAGE;
                $row['banner_main'] = base_url() . NO_IMAGE;
            } else {
                $row['banner_main'] = base_url($row['banner']);
                $row['banner'] = get_image_url($row['banner'], 'thumb', 'sm');
            }
            $tempRow['banner'] = "<div class='image-box-100' > <a href='" . $row['banner_main'] . "' data-toggle='lightbox' data-gallery='gallery'> <img src='" . $row['banner'] . "' class='img-fluid w-50'></a></div>";

            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function add_category($data)
    {
        $data = escape_array($data);

        if (isset($data['edit_category']) && !empty($data['edit_category'])) {
            $category_id = fetch_details('categories', ['id' => $data['edit_category']]);
            $category_name = $category_id[0]['name'];
        } else {
            $category_id = "";
            $category_name = "";
        }
        if ($category_name != $data['category_input_name']) {
            $cat_data = [
                'name' => $data['category_input_name'],
                'parent_id' => (isset($data['category_parent']) && !empty($data['category_parent'])) ? $data['category_parent'] : '0',
                'slug' => create_unique_slug($data['category_input_name'], 'categories'),
                'status' => '1',
            ];
        } else {
            $cat_data = [
                'name' => $data['category_input_name'],
                'parent_id' => (isset($data['category_parent']) && !empty($data['category_parent'])) ? $data['category_parent'] : '0',
                'status' => '1',
            ];
        }

        if (isset($data['edit_category']) && !empty($data['edit_category'])) {

            unset($cat_data['status']);
            if (isset($data['category_input_image']) && !empty($data['category_input_image'])) {
                $cat_data['image'] = $data['category_input_image'];
            }

            $cat_data['banner'] = (isset($data['banner'])) ? $data['banner'] : '';

            $this->db->set($cat_data)->where('id', $data['edit_category'])->update('categories');
        } else {
            if (isset($data['category_input_image']) && ($data['category_input_image'])) {
                $cat_data['image'] = $data['category_input_image'];
            }
            if (isset($data['banner']) && !empty($data['banner'])) {
                $cat_data['banner'] = (isset($data['banner']) && !empty($data['banner'])) ? $data['banner'] : '';
            }
            $this->db->insert('categories', $cat_data);
        }
    }

    public function top_category()
    {
        $query = $this->db->select('*')
            ->where('status', 1)
            ->limit('4')
            ->order_by('clicks', 'Desc')
            ->get('categories');

        $data['total'] = $query->num_rows();
        $data['rows'] = $query->result_array();

        print_r(json_encode($data));
    }
    public function get_products_by_category($category_id)
    {
        $this->db->where('category_id', $category_id);
        $query = $this->db->get('products'); // Assuming 'products' is your table name
        return $query->result_array();
    }
    public function has_child_categories($category_id)
{
    return $this->db
        ->where('parent_id', $category_id)
        ->count_all_results('categories') > 0;
}
}
