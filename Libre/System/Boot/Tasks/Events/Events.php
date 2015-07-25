<?php

namespace Libre\System\Boot\Tasks {

    use Libre\Patterns\Observer;

    class Events extends Observer{
        /**
         * Event->_statement
         * @param \SplSubject $subject
         */
        public function update( \SplSubject $subject ) {
            $statement = $subject->getStatement();
            // Callbacks
            switch($statement) {
                case 'init':
                    //echo $subject->getName()." : init<br>";
                    break;

                case 'started':
                    //echo $subject->getName()." : started<br>";
                    // Callbacks
                    break;

                case 'ended':
                    //echo $subject->getName()." : ended<br>";
                    // Callbacks
                    break;
            }
        }
    }
}