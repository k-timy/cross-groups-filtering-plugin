

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  Cross Groups Filtering
 *
 */

$plugin_info       = array(
    'pi_name'        => 'Cross Groups Filtering',
    'pi_version'     => '1.0',
    'pi_author'      => 'Kourosh Teimouri',
    'pi_author_url'  => 'https://github.com/k-timy',
    'pi_description' => 'Filters entries from different category groups',
    'pi_usage'       => Cross_Groups_Filtering::usage()
);

class Cross_Groups_Filtering
{
    public $return_data  = "";
    public function Cross_Groups_Filtering()
    {
        $filters = ee()->TMPL->fetch_param('filters');
        $group_cats = array();
        $group_cat_pairs = explode(";",$filters);
        foreach($group_cat_pairs as $group_cat_pair){
            $pair = explode(":",$group_cat_pair);
            $cats = explode(",",$pair[1]);
            $cats_count = count($cats);
            for($i = 0 ; $i < $cats_count ; $i++){
                $cats[$i] = "'".$cats[$i]."'";
            }
            $pair[1] = implode(",",$cats);

            $sql = 'select group_id as gid,group_concat(cat_id) as cats_str from exp_categories where cat_name in ('.$pair[1].')';
            $cat_ids = $this->processQuery($sql);
            $group_cats[] = array($pair[0],$cat_ids[0]['cats_str']);
        }
        $cats = $group_cats[0][1];
        $sql = 'select * from exp_category_posts where cat_id in ('.$cats.')';

        $posts = $this->processQuery($sql);

        $post_ids = array();
        foreach($posts as $post)
        {
            $post_ids[] = $post['entry_id'];
        }
        $post_ids = implode(',',$post_ids);

        $sql = 'select * from exp_category_posts where entry_id in ('.$post_ids.')';
        $posts = $this->processQuery($sql);
        $newPosts = array();
        foreach($posts as $post)
        {
            $ent_id = $post['entry_id'];
            $cat_id = $post['cat_id'];
            $newPosts[strval($ent_id)][] = strval($cat_id);
        }
        $posts = $newPosts;
        $group_counts = count($group_cats);
        for($i = 1 ; $i < $group_counts ; $i++){
            $cats_str = $group_cats[$i][1];
            $filtered_posts = array();
            foreach($posts as $ent_id => $ent_cats)
            {
                foreach($ent_cats as $catId)
                {
                    if(strpos($cats_str,strval($catId)) !== FALSE)
                    {
                        $filtered_posts[$ent_id] = $ent_cats;
                    }
                }
            }
            $posts = $filtered_posts;

        }
        $ids = array();
        foreach($posts as $k => $v){
            $ids[] = $k;
        }

        $ids = '0|'.implode('|',$ids);
        $this->return_data = str_replace('{piped_entry_ids}', $ids,ee()->TMPL->tagdata);
    }

    // ----------------------------------------
    //  Plugin Usage
    // ----------------------------------------

    // This function describes how the plugin is used.
    //  Make sure and use output buffering

    public static function usage()
    {
        if(REQ === 'CP')
            return file_get_contents(dirname(__FILE__).'/README.md');
        return null;
//        ob_start();
//        ?>
<!---->
<!--       -->
<!---->
<!---->
<!--        --><?php
//        $buffer = ob_get_contents();
//
//        ob_end_clean();
//
//        return $buffer;
    }

    function processQuery($sql)
    {
        $query = ee()->db->query($sql);
        $results = $query->result_array();
        if ($query->num_rows() == 0)
        {
            return null;
        }
        return $results;
    }
}

?>