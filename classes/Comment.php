<?php

    class Comment{
        
        private $id;
        private $user_id;
        private $prompt_id;
        private $content;
        private $timestamp;
        private $isDeleted;

        public function setId($commentId){
            $this->id = $commentId;
        }

        public function setUserId($userId){
            $this->user_id = $userId;
        }

        public function setPromptId($promptId){
            $this->prompt_id = $promptId;
        }

        public function setContent($content){
            $this->content = $content;
        }

        public function setTimeStamp($timestamp){
            $this->timestamp = $timestamp;
        }

        public function setIsDeleted($isDeleted){
            $this->isDeleted = $isDeleted;
        }

        public function getId(){
            return $this->id;
        }

        public function getUserId(){
            return $this->user_id;
        }

        public function getPromptId(){
            return $this->prompt_id;
        }

        public function getContent(){
            return $this->content;
        }

        public function getDatetime(){
            return $this->timestamp;
        }

        public function getIsDeleted(){
            return $this->isDeleted;
        }


        public static function getCommentsfromPrompt($prompt_id){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM comments WHERE prompt_id = :prompt_id AND isDeleted = 0");
            $statement->bindValue(":prompt_id", $prompt_id);
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;
        }

        public function saveComment(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("INSERT INTO comments (user_id, prompt_id, content, timestamp) VALUES (:user_id, :prompt_id, :content, :datetime)");
            $statement->bindValue(":user_id", $this->user_id);
            $statement->bindValue(":prompt_id", $this->prompt_id);
            $statement->bindValue(":content", $this->content);
            $statement->bindValue(":datetime", $this->timestamp);
            $result = $statement->execute();
            return $result;
        }

    }