@extends('layouts.frontend')
@section('style')
<style>
.center {
    color: white;
    text-align: center;
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    margin: auto;
}
.fullscreenDiv {
    position: relative;
    width: 100%;
    height: 100vh;
    margin: auto;
    background-color: rgb(129, 99, 223);
}
body{
  height: 100vh;
  display:flex;
  background: azure;
}
</style>
@endsection
@section('section')
<div class='fullscreenDiv'>
    <div class="center">
        <h1>Thank you!</h1>
        <h3>You've completed the interview</h3>
        <h3>We will review your results</h3>
        <h3>If you have any questions, fell free to contact us</h3>
    </div>
</div>​
@endsection
