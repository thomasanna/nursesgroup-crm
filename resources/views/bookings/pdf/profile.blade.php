<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <title></title>
      <style>
        body{width: 100%;margin: 0;padding: 0;}
        .header{width: 100%;}

        .logo {}
        .logo img,.photo img  {width: 280px;}
        .heading {font-size: 22px;text-decoration: underline;font-weight: bold;text-align: center;}

        .cf:before, .cf:after { content: " "; display: table; }
        .cf:after { clear: both; }
        .cf { *zoom: 1; }

        .basic {width: 100%; margin-top: 20px; }
        .dts {width:70%; float: left;}
        .dts div {line-height: 27px;}
        .name {font-size: 24px;font-weight: 600;color: #a20606;}
        .cat,.staffId {font-size: 19px;}

        .photo { width:30%; float: right; }
        .photo .imgHldr { text-align: center; }
        .photo .imgHldr img {width: 140px;height: 150px; }
        .smallDet { background: #6273ec;color: #fff; }
        .smallDet .smName,.smallDet .smCat{ text-align: center; font-size: 16px;font-weight: 600;}

         .content{width: 100%;margin-top: 30px;}

         .content table{width: 80%; margin: 0 auto;border-collapse: collapse;border: 1px solid black;}
         .dbs table tr {text-align: center;}
         .training .head,.registration .head {
         margin: 50px 0 15px 0;
         text-transform: uppercase;
         text-align: center;
         }
         th{text-decoration: underline;}
          td,th{
         padding-left: 5px;
         }

         .training td:last-child,
         .registration td:last-child,
         .training th:last-child,
         .registration th:last-child{
          text-align: right;
          padding-right: 10px;
         }
         .training tr,.dbs tr,.registration tr {
         line-height: 27px;
         }
         .registration table thead th {
         text-align: left;
         }
         .queries {
         margin: 50px 0 15px 0;
         text-transform: capitalize;
         text-align: center;
         }
      </style>
   </head>
   <body>
      <div class="header">
         <div class="logo"><img src="{{asset('public/images/Nurses_Group_Logo.jpg')}}" alt="Nurses Group" /></div>
         <div class="heading">Staff Profile</div>
         <div class="basic cf">
            <div class="dts">
               <div class="name">{{$staff->forname}} {{$staff->surname}}</div>
               <div class="cat">{{$staff->category->name}}</div>
               <div class="staffId">Staff Id: {{$staff->staffId}} </div>
            </div>
            <div class="photo">
               <div class="imgHldr">
                  <img src="{{asset('storage/app/staff/photo/'.$staff->photo)}}" alt="Nurses Group" />
               </div>
               <div class="smallDet">
                  <div class="smName">{{$staff->forname}} {{$staff->surname}}</div>
                  <div class="smCat">{{$staff->category->name}}</div>
               </div>
            </div>
         </div>
      </div>
      <div class="content">
         <div class="dbs">
            <table>
               <thead>
                  <tr>
                     <th>Right to work in UK</th>
                     <th>Proof of ID Checked</th>
                     <th>References Received</th>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <td>Yes</td>
                     <td>Yes</td>
                     <td>Yes</td>
                  </tr>
               </tbody>
            </table>
         </div>
         <div class="training">
            <div class="head">MANDATORY TRAINGING ATTENDED</div>
            <table>
               <thead>
                  <tr>
                     <th>TRAINING</th>
                     <th>MONTH ATTENDED/ REFRESHED</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($staff->training as $train)
                  <tr>
                     <td>{{$train->course->courseName}}</td>
                     <td>{{date('M-Y',strtotime($train->issueDate))}}</td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
         <div class="registration">
            <div class="head">Registration – (Only for Nurses) </div>
            <table>
               <thead>
                  <tr>
                     <th>NMC – PIN</th>
                     <th>NMC PIN</th>
                     <th>REVALIDATION DATE</th>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <td>@isset($staff->nmcPinNumber) Verified & Confirmed @endisset</td>
                     <td>{{$staff->nmcPinNumber}}</td>
                     <td>{{date('M-Y',strtotime($staff->nmcPinReValidationDate))}}</td>
                  </tr>
               </tbody>
            </table>
         </div>
         <div class="queries">Any further quires, please contact 01935315031</div>
      </div>
      <div class="footer">
      </div>
   </body>
</html>
