<?php

session_start();

if( isset($_SESSION["usuario"]) ) {
    echo "<script>
            alert('Compra efetuada com sucesso! ✅')
        <script>";
} else {
    echo "<script>
           
        <script>";
}