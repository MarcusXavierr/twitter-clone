<?php

namespace App\Models;

use MF\Model\Model;

class Tweet extends Model{
    private $id;
    private $id_usuario;
    private $tweet;
    private $data;

    public function salvar(){
        $query = "insert into tweets (id_usuario, tweet) values (:id_usuario, :tweet)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario',$this->getId_usuario());
        $stmt->bindValue(':tweet',$this->getTweet());

        $stmt->execute();

        return $this;
    }

    public function getAll(){
        $query = "select 
        t.id, u.nome,t.id_usuario, t.tweet, DATE_FORMAT(CONVERT_TZ(t.data,'+03:00',@@global.time_zone),'%d/%m/%Y %H:%i') as data
        from tweets as t join usuarios as u
        on t.id_usuario = u.id
        where t.id_usuario = :id_usuario or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores where id_usuario = :id_usuario )
        order by t.data desc";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario',$this->getId_usuario());
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function apagar(){
        $query = "delete from tweets where id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id',$this->getId());
        $stmt->execute();
        return true;
    }

    //Getters e Setters
    public function getId()
    {
        return $this->id;
    }


    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

     
    public function getId_usuario()
    {
        return $this->id_usuario;
    }

   
    public function setId_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }

    
    public function getTweet()
    {
        return $this->tweet;
    }

    
    public function setTweet($tweet)
    {
        $this->tweet = $tweet;

        return $this;
    }

    
    public function getData()
    {
        return $this->data;
    }

    
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}

?>