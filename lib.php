<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Grading method controller for the self-assessment plugin
 *
 * @package    gradingform_selfassessment
 * @copyright  2017 University of Helsinki
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/grade/grading/form/lib.php');

/**
 * This controller encapsulates the self-assessment grading logic
 *
 * @package    gradingform_selfassessment
 * @copyright  2017 University of Helsinki
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class gradingform_selfassessment_controller extends gradingform_controller {

    /**
     * Loads the submarker completed exercises if it exists.
     *
     */
    protected function load_definition() {
        //The definition is ready by default
        $this->definition->status = self::DEFINITION_STATUS_READY;

        //
        $this->definition->timecopied = 2;
        $this->definition->timemodified = 2; 
    }

    /**
     * Calculates and returns the possible minimum and maximum score (in points) for this self-assessment
     *
     * @return array
     */
    public function get_min_max_score() {
        $returnvalue['minscore'] += 0;
        $returnvalue['maxscore'] += 20;
        return $returnvalue;
    }

    /**
     * Returns the HTML code displaying the preview of the grading form
     *
     * @param moodle_page $page the target page
     * @return string
     */
    public function render_preview(moodle_page $page) {
        return '<div><canvas id="stage" height="400" width="520"></canvas>' . 
        "<script> 
        /**
         * Thanks for the script Caio Paiola.
         * Namespace
         */
        var Game      = Game      || {};
        var Keyboard  = Keyboard  || {}; 
        var Component = Component || {};
       
        /**
         * Keyboard Map
         */
        Keyboard.Keymap = {
          37: 'left',
          38: 'up',
          39: 'right',
          40: 'down'
        };
       
        /**
         * Keyboard Events
         */
        Keyboard.ControllerEvents = function() {
         
         // Setts
         var self      = this;
         this.pressKey = null;
         this.keymap   = Keyboard.Keymap;
         
         // Keydown Event
         document.onkeydown = function(event) {
           self.pressKey = event.which;
         };
         
         // Get Key
         this.getKey = function() {
           return this.keymap[this.pressKey];
        };
        };
       
       /**
        * Game Component Stage
        */
       Component.Stage = function(canvas, conf) {  
         
         // Sets
         this.keyEvent  = new Keyboard.ControllerEvents();
         this.width     = canvas.width;
         this.height    = canvas.height;
         this.length    = [];
         this.food      = {};
         this.score     = 0;
         this.direction = 'right';
         this.conf      = {
           cw   : 10,
           size : 5,
           fps  : 1000
         };
         
         // Merge Conf
         if (typeof conf == 'object') {
           for (var key in conf) {
             if (conf.hasOwnProperty(key)) {
               this.conf[key] = conf[key];
             }
           }
         }
         
       };
       
       /**
        * Game Component Snake
        */
       Component.Snake = function(canvas, conf) {
         
         // Game Stage
         this.stage = new Component.Stage(canvas, conf);
         
         // Init Snake
         this.initSnake = function() {
           
           // Itaration in Snake Conf Size
           for (var i = 0; i < this.stage.conf.size; i++) {
             
             // Add Snake Cells
             this.stage.length.push({x: i, y:0});
               }
           };
         
         // Call init Snake
         this.initSnake();
         
         // Init Food  
         this.initFood = function() {
               
           // Add food on stage
           this.stage.food = {
                   x: Math.round(Math.random() * (this.stage.width - this.stage.conf.cw) / this.stage.conf.cw), 
                   y: Math.round(Math.random() * (this.stage.height - this.stage.conf.cw) / this.stage.conf.cw), 
               };
           };
         
         // Init Food
         this.initFood();
         
         // Restart Stage
         this.restart = function() {
           this.stage.length            = [];
           this.stage.food              = {};
           this.stage.score             = 0;
           this.stage.direction         = 'right';
           this.stage.keyEvent.pressKey = null;
           this.initSnake();
           this.initFood();
         };
       };
       
       /**
        * Game Draw
        */
       Game.Draw = function(context, snake) {
         
         // Draw Stage
         this.drawStage = function() {
           
           // Check Keypress And Set Stage direction
           var keyPress = snake.stage.keyEvent.getKey(); 
           if (typeof(keyPress) != 'undefined') {
             snake.stage.direction = keyPress;
           }
           
           // Draw White Stage
               context.fillStyle = 'green';
               context.fillRect(0, 0, snake.stage.width, snake.stage.height);
               
           // Snake Position
           var nx = snake.stage.length[0].x;
               var ny = snake.stage.length[0].y;
               
           // Add position by stage direction
           switch (snake.stage.direction) {
             case 'right':
               nx++;
               break;
             case 'left':
               nx--;
               break;
             case 'up':
               ny--;
               break;
             case 'down':
               ny++;
               break;
           }
           
           // Check Collision
           if (this.collision(nx, ny) == true) {
             snake.restart();
             return;
           }
           
           // Logic of Snake food
           if (nx == snake.stage.food.x && ny == snake.stage.food.y) {
             var tail = {x: nx, y: ny};
             snake.stage.score++;
             snake.initFood();
           } else {
             var tail = snake.stage.length.pop();
             tail.x   = nx;
             tail.y   = ny;	
           }
           snake.stage.length.unshift(tail);
           
           // Draw Snake
           for (var i = 0; i < snake.stage.length.length; i++) {
             var cell = snake.stage.length[i];
             this.drawCell(cell.x, cell.y);
           }
           
           // Draw Food
           this.drawCell(snake.stage.food.x, snake.stage.food.y);
           
           // Draw Score
           context.fillText('Score: ' + snake.stage.score, 5, (snake.stage.height - 5));
         };
         
         // Draw Cell
         this.drawCell = function(x, y) {
           context.fillStyle = 'rgb(170, 170, 170)';
           context.beginPath();
           context.arc((x * snake.stage.conf.cw + 6), (y * snake.stage.conf.cw + 6), 4, 0, 2*Math.PI, false);    
           context.fill();
         };
         
         // Check Collision with walls
         this.collision = function(nx, ny) {  
           if (nx == -1 || nx == (snake.stage.width / snake.stage.conf.cw) || ny == -1 || ny == (snake.stage.height / snake.stage.conf.cw)) {
             return true;
           }
           return false;    
           }
       };
       
       
       /**
        * Game Snake
        */
       Game.Snake = function(elementId, conf) {
         
         // Sets
         var canvas   = document.getElementById(elementId);
         var context  = canvas.getContext('2d');
         var snake    = new Component.Snake(canvas, conf);
         var gameDraw = new Game.Draw(context, snake);
         
         // Game Interval
         setInterval(function() {gameDraw.drawStage();}, snake.stage.conf.fps);
       };
       
       
       /**
        * Window Load
        */
       window.onload = function() {
         var snake = new Game.Snake('stage', {fps: 100, size: 4});
       };
       </script>" .  ' </div>';
    }

    /**
     * Deletes the guide definition and all the associated information
     */
    protected function delete_plugin_definition() {
        global $DB;
    }

    /**
     * Bad way to skip creating the form.
     * TODO: find a better solution.
     */
    public function is_form_defined() {
        return true;
    }

    /**
     * Would return URL of a page where the grading form can be defined and edited.
     * But we only want to redirect back to viewing the settings.
     *
     * @param moodle_url $returnurl     
     * @return moodle_url
     */
    public function get_editor_url(moodle_url $returnurl = null) { 
        if (!is_null($returnurl)) {
            $params['returnurl'] = $returnurl->out(false);
        }   
        return new moodle_url('/grade/grading/manage.php?areaid='.$this->get_areaid(), $params);
    }
}

/**
 * Class to manage one self-assessment grading instance.
 *
 * Stores information and performs actions like update, copy, validate, submit, etc.
 *
 * @package    gradingform_selfassessment
 * @copyright  2017 University of Helsinki
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class gradingform_selfassessment_instance extends gradingform_instance {

    /**
     * Updates the instance with the data received from grading form. This function may be
     * called via AJAX when grading is not yet completed, so it does not change the
     * status of the instance.
     *
     * @param array $data
     */
    public function update($data) {
        global $DB;
    }

    /**
     * Calculates the grade to be pushed to the gradebook
     *
     * @return float|int the valid grade from $this->get_controller()->get_grade_range()
     */
    public function get_grade() {
        return 0;
    }


    /**
     * Returns html for form element of type 'grading'.
     *
     * @param moodle_page $page
     * @param MoodleQuickForm_grading $gradingformelement
     * @return string
     */
    public function render_grading_element($page, $gradingformelement) {
        return "<div> render_grading_element </div>";
    }
}
