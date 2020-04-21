<?php

namespace App\Models;

use MF\Model\Model;

class Usuario extends Model{

    private $id;
    private $nome;
    private $email;
    private $senha;

    public function getInfoUsuario(){
        $query = "select nome from usuarios where id = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->getId());
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function getTotalTweets(){
        $query = "select count(*) as total_tweet from tweets where id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->getId());
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function getTotalSeguindo(){
        $query = "select count(*) as total_seguindo from usuarios_seguidores where id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->getId());
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function getTotalSeguidores(){
        $query = "select count(*) as total_seguidores from usuarios_seguidores where id_usuario_seguindo = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->getId());
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }


    public function seguirUsuario($id_usuario){
        $query = "insert into 
        usuarios_seguidores 
        (id_usuario,id_usuario_seguindo)
        values
        (:id_usuario,:id_usuario_seguindo)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario',$this->getId());
        $stmt->bindValue(':id_usuario_seguindo',$id_usuario);
        $stmt->execute();
    }

    public function deixarSeguir($id_usuario){
        $query = "delete from usuarios_seguidores
         where id_usuario = :id_usuario and
          id_usuario_seguindo = :id_usuario_seguindo";

          $stmt = $this->db->prepare($query);
          $stmt->bindValue(':id_usuario', $this->getId());
          $stmt->bindValue(':id_usuario_seguindo',$id_usuario); 
          $stmt->execute();

          return true;
    }

    public function getAll(){
        $query = "select 
                    u.id,u.nome,u.email, (
                        select count(*) from 
                        usuarios_seguidores as us 
                        where us.id_usuario = :id_usuario and us.id_usuario_seguindo = u.id
                    ) as seguindo_sn
                from 
                    usuarios as u
                where 
                    u.nome like :nome and u.id != :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome','%'.$this->getNome().'%');
        $stmt->bindValue(':id_usuario',$this->getId());
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function autenticar(){
        $query = "
            select id, email,nome from usuarios where email = :email and senha = :senha
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->getEmail());
        $stmt->bindValue(':senha', $this->getSenha());
        $stmt->execute();

        $usuario = $stmt->fetch(\PDO::FETCH_OBJ);
        if($usuario->id != '' && $usuario->nome != ''){
            $this->setId($usuario->id);
            $this->setNome($usuario->nome);
            return $this;
        }

    }

    public function salvar(){
        $query = "
            insert into usuarios (nome,email,senha) values (:nome,:email,:senha)    
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome',$this->getNome());
        $stmt->bindValue(':email',$this->getEmail());
        $stmt->bindValue(':senha',$this->getSenha());
        $stmt->execute();
        return $this;
    }

    public function validarCadastro(){
        $valido = true;

        if(strlen($this->getNome()) < 3){
            $valido = false;
        }
        if(strlen($this->getEmail()) < 3){
            $valido = false;
        }
        if(strlen($this->getSenha()) < 3){
            $valido = false;
        }


        return $valido;
    }

    public function getUsuarioPorEmail(){
        $query = "
            select nome,email from usuarios where email = :email
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email',$this->getEmail());
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
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

    
    public function getNome()
    {
        return $this->nome;
    }

 
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }


    public function getEmail()
    {
        return $this->email;
    }


    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }


    public function getSenha()
    {
        return $this->senha;
    }


    public function setSenha($senha)
    {
        $this->senha = $senha;

        return $this;
    }
}

?>