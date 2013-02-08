<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Static_pages extends CI_Controller {

  /**
   * Index Page for this controller.
   *
   * Maps to the following URL
   *    http://example.com/index.php/welcome
   *  - or -  
   *    http://example.com/index.php/welcome/index
   *  - or -
   * Since this controller is set as the default controller in 
   * config/routes.php, it's displayed at http://example.com/
   *
   * So any other public methods not prefixed with an underscore will
   * map to /index.php/welcome/<method_name>
   * @see http://codeigniter.com/user_guide/general/urls.html
   */
  public function index()
  {
    $data['title'] = "Login Page";

    

    $params = $this->loadParam();

    $data['round'] = $params[0][1];

    //var_dump($params);
    //die();

    $this->load->view('header', $data);
    $this->load->view('index_tournament');
    $this->load->view('footer');
  }

  public function load()
  {
    $data['title'] = "Login Page";
    $params = $this->loadParam();




    $round = trim($params[0][1]);
    $state = $params[1][1];
    $motion = $params[2][1];
    $msg = $params[3][1];

    $message ="";

    if ($state == 0 )
    {
      $message .= "<div class='content'><br/><br/><h1>$msg</h1></div>";
    } 

    if ($state == 2 )
    {
      $message .= "<div class='content'><br/><h1>$motion</h1></div>";
    }

    if ($state == 3)
    {
        $page = "";
        if (!isset($_REQUEST['p']))
          $page = 1;
        else
          $page = $_REQUEST['p'];



        $total = round ($this->db->count_all_results("team") / 8 , 0, PHP_ROUND_HALF_UP );

        if ($page > $total)
          $page = 1;


        $this->db->select('*');
        $this->db->from("draw_round_$round");
        $this->db->join("venue", "venue.venue_id = draw_round_$round.venue_id");
        $this->db->join("temp_result_round_$round", "draw_round_$round.debate_id = temp_result_round_$round.debate_id");

        $rooms = $this->db->get();

        //var_dump($rooms->result());
        //die();


        $this->db->select('*');
        $this->db->from("team");
        $this->db->join("university", "team.univ_id = university.univ_id");
        $this->db->limit(10, 10 * ($page - 1));


        $page +=1;
        $teams_temp = $this->db->get();

        $teams = array();

        foreach($teams_temp->result() as $team )
        {
          $teams[$team->team_id] = $team;
        }

        //$this->db_second = $this->CI->load->database('second', TRUE); 

        $second = $this->load->database('second', TRUE); 
        $this->db->select('*');
        $this->db->from("draw_round_$round");
        $this->db->join("venue", "venue.venue_id = draw_round_$round.venue_id");
        $this->db->join("temp_result_round_$round", "draw_round_$round.debate_id = temp_result_round_$round.debate_id");

        $rooms2 = $this->db->get();
        $message .= "<div class='content'>";
        $message .="<span id='p'>".($page-1)."</span>/".$total;
        $message .= "<table class='table table-striped table-condensed'>";

        $message .= "<tr>";
        $message .= "<th>Team</th>";
        $message .= "<th>1st Server</th>";
        $message .= "<th>2nd Server</th>";
        $message .= "<th>Result</th>";
        $message .= "</tr>";
        $count = 0;
        foreach ( $teams as $key => $value )
        { 
          if ($count % 2 == 0)
            $message .="<tr style='background-color:#FCC;'>";
          else
            $message .= "<tr style='background-color:#E2E2C5; !important'>";

          $count++;

          $message .="<td>".$value->univ_code." ".$value->team_code."</td>";

          $result1 = "";
          $result2 = "";

          foreach ($rooms->result() as $room)
          {
            if ($room->first == $key )
            {
              $message .="<td>OK</td>";
              $result1 = "First";

            }
            if ($room->second == $key )
            {
              $message .="<td>OK</td>";
              $result1 = "Second";
            }
            if ($room->third == $key )
            {
              $message .="<td>OK</td>";
              $result1 = "Third";
            }
            if ($room->fourth == $key )
            {
              $message .="<td>OK</td>";
              $result1 = "Fourth";
            }

          }
            if ( empty ($result1) )
            {
              $message .= "<td></td>";
            }
          foreach ($rooms2->result() as $room)
          {
            if ($room->first == $key )
            {
              $message .="<td>OK</td>";
              $result2 = "First";
            }
            if ($room->second == $key )
            {
              $message .="<td>OK</td>";
              $result2 = "Second";
            }
            if ($room->third == $key )
            {
              $message .="<td>OK</td>";
              $result2 = "Third";
            }
            if ($room->fourth == $key )
            {
              $message .="<td>OK</td>";
              $result2 = "Fourth";
            }

            

          }

          if ( empty ($result2) )
            {
              $message .= "<td></td>";
            }

          if ( !empty($result1) && !empty($result2) )
          {
            if ( $result1 == $result2  )
            {
              $message .="<td>".$result1."</td>";
            }
            else
            {
              $message .="<td>Conflict.</td>";
            }


          }
          else
          {
            $message .= "<td>?</td>";
          }

          $message .= "</tr>";
        }



        $message .= "</table>";
        $message .= "<script type='text/javascript'>$('#page').val('".$page."');</script>";
        $message .= "</div>";



    }

    if ($state == 1)
    {

        $page = "";
        if (!isset($_REQUEST['p']))
          $page = 1;
        else
          $page = $_REQUEST['p'];



        $total = round ($this->db->count_all_results("draw_round_$round") / 4 , 0, PHP_ROUND_HALF_UP );

        if ($page > $total)
          $page = 1;


        $this->db->select('*');
        $this->db->from("draw_round_$round");
        $this->db->join("venue", "draw_round_$round.venue_id = venue.venue_id ");
        $this->db->limit(4, 4 * ($page - 1));


        $page +=1;

        $rooms = $this->db->get();

        //var_dump($rooms->result());
        //die();


        $this->db->select('*');
        $this->db->from("team");
        $this->db->join("university", "team.univ_id = university.univ_id");
        $teams_temp = $this->db->get();

        $teams = array();
        foreach($teams_temp->result() as $team )
        {
          $teams[$team->team_id] = $team;
        }

        $this->db->select('*');
        $this->db->from("adjudicator");
        $this->db->join("adjud_round_$round", "adjud_round_$round.adjud_id = adjudicator.adjud_id");

        $adju_temp = $this->db->get();
        $adjus = array();

        foreach ($adju_temp->result() as $adju)
        {
          $adjus[] = $adju;
        }



        

        $message = "<div class='content'>";
        $message .= "<span id='p'>".($page-1)."</span>/".$total;
        $message .= "<table class='table  table-condensed'>";

        $message .= "<tr>";
        $message .= "<th>Room</th>";
        $message .= "<th>OG</th>";
        $message .= "<th>OO</th>";
        $message .= "<th>CG</th>";
        $message .= "<th>CO</th>";
        $message .= "<th>Chair</th>";
        $message .= "<th>Panel</th>";
        $message .= "<th>Trainee</th>";
        $message .= "</tr>";
        $count = 0;
        foreach ($rooms->result() as $room)
        {
          if ($count % 2 == 0)
            $message.="<tr style='background-color:#FCC;'>";
          else
            $message.="<tr style='background-color:#E2E2C5;'>";

          $count++;

          $message.="<td>$room->venue_name</td>";
          $message.="<td>".$teams[$room->og]->univ_code." ".$teams[$room->og]->team_code."</td>";
          $message.="<td>".$teams[$room->oo]->univ_code." ".$teams[$room->oo]->team_code."</td>";
          $message.="<td>".$teams[$room->cg]->univ_code." ".$teams[$room->cg]->team_code."</td>";
          $message.="<td>".$teams[$room->oo]->univ_code." ".$teams[$room->oo]->team_code."</td>";


          //var_dump($adjus);
          //die();

          $message.= "<td>";
            foreach($adjus as $adju)
            {
              if ($adju->debate_id == $room->debate_id && $adju->status=="chair")
              {
                $message.="$adju->adjud_name <br/><br/>";
              }
            }
  
          $message.= "</td>";

          $message.= "<td>";
            foreach($adjus as $adju)
            {
              if ($adju->debate_id == $room->debate_id && $adju->status=="panelist")
              {
                $message.="$adju->adjud_name <br/><br/>";
              }
            }
  
          $message.= "</td>";

          $message.= "<td>";
            foreach($adjus as $adju)
            {
              if ($adju->debate_id == $room->debate_id && $adju->status=="trainee")
              {
                $message.="$adju->adjud_name <br/><br/>";
              }
            }
  
          $message.= "</td>";

          $message.="</tr>";
        }

        $message .= "</table>";
        $message .= "<script type='text/javascript'>$('#page').val('".$page."');</script>";
        $message .= "</div>";


    }

    echo $message;
    //var_dump($params);
    //die();

    //echo "<script type='text/javascript'>alert ('hello');</script>";
  }

  
  private function loadParam()
  {
    $params = array();
    $file_handle = fopen("application/param", "r");
    while (!feof($file_handle)) {
       $line = fgets($file_handle);
       $params[] = explode("|", $line);
    }
    fclose($file_handle);
    return $params;
  } 

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */