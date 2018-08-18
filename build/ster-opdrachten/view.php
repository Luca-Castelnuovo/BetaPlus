<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login();

head('Naam Steropdracht || Ster Opdrachten', 2, 'Naam Ster Opdrachte');
?>

<div class="section">
   <div class="container">
      <div class="row">
         <div class="col s12">
            <div class="container">
               <div class="card-panel center">
                  <div class="card-image">
                     <img src="http://via.placeholder.com/400x300?text=placeholder">
                  </div>
                  <h1 class="center">Naam Steropdracht</h1>
                  <h6 class="center flow-text">
                  Door: <a href="leerlingen.php?id=1">Leerling een</a> en <a href="leerlingen.php?id=2">Leerling twee</a>.</h1>
               </div>
            </div>
            <div class="container">
               <div class="card-panel center">
                  <h3 class="center">Review Details</h3>
                  <table class="responsive-table center grey lighten-3">
                     <thead>
                        <tr>
                           <th>Feedback</th>
                           <th>Status</th>
                           <th>
                              Grade
                           </th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td><a href="">Feedback Given</a></td>
                           <td>No Go</td>
                           <td>
                              <a href="">ABCD</a>
                           </td>
                        </tr>
                        <tr>
                           <td>30-07-2018</td>
                           <td>2-08-2018</td>
                           <td>5-08-2018</td>
                        </tr>
                     </tbody>
                  </table>
                  <h3 class="center">Date Details</h3>
                  <table class="responsive-table center grey lighten-3">
                     <tbody>
                        <tr>
                           <td>Date Created:</td>
                           <td>Date Edited:</td>
                           <td>Date Finished:</td>
                        </tr>
                        <tr>
                           <td>17-07-2018</td>
                           <td>30-07-2018</td>
                           <td>2-08-2018</td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
            <!-- PARAGRAPH -->
            <div class="container">
               <h2 class="truncate">Intro</h2>
               <p class="flow-text">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
               </p>
            </div>
            <!-- TABLE -->
            <div class="container">
               <table class="responsive-table striped">
                  <thead>
                     <tr>
                        <th>Name</th>
                        <th>Item Name</th>
                        <th>Item Price</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>Alvin</td>
                        <td>Eclair</td>
                        <td>$0.87</td>
                     </tr>
                     <tr>
                        <td>Alan</td>
                        <td>Jellybean</td>
                        <td>$3.76</td>
                     </tr>
                     <tr>
                        <td>Jonathan</td>
                        <td>Lollipop</td>
                        <td>$7.00</td>
                     </tr>
                  </tbody>
               </table>
            </div>
            <div class="container">
               <h2 class="truncate">Middenstuk</h2>
               <p class="flow-text">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
               </p>
            </div>
            <!-- IMAGE -->
            <div class="container center">
               <img src="http://via.placeholder.com/400x300?text=placeholder">
               <h6 class="grey-text">Image Description</h6>
            </div>
            <div class="container">
               <h2 class="truncate">Conclusie</h2>
               <p class="flow-text">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                  in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
               </p>
            </div>
         </div>
      </div>
   </div>
</div>

<?php footer(); ?>
