<?php

$qtySelect = "<select id='itemQty' class='mainSelect'>
<option disabled selected>#</option>
<option>1</option>
<option>2</option>
<option>3</option>
<option>4</option>
<option>5</option>
<option>6</option>
<option>7</option>
<option>8</option>
<option>9</option>
<option>10</option>
<option>11</option>
<option>12</option>
<option>13</option>
<option>14</option>
<option>15</option>
<option>16</option>
<option>17</option>
<option>18</option>
<option>19</option>
<option>20</option>
</select>";

$staSelect = "<select id='staSelect' class='mainSelect'>
<option>Reserved</option>
<option>Ready</option>
<option>In ERP</option>
<option>Archived</option>
<option>Recent 100</option>
</select>";

echo "<table class='table1'>
<tr>
    <td>Reserve New Item IDs</td>
    <td>$qtySelect</td>
    <td><button onclick='getNumbers()' class='mainBtn'>Reserve</button></td>
    <td class='explain'>Reserve a single Item ID or block of IDs (up to 20)</td>
</tr>
<tr>
    <td>Configure Item IDs</td>
    <td><input class='item' type='text' placeholder='Base #' id='baseID' class='baseBox'></td>
    <td><button onclick='configure(0)' class='mainBtn'>Configure</button></td>
    <td class='explain'>Configure Item IDs you have already reserved.</td>
</tr>
<tr>
    <td>View My Items</td>
    <td>$staSelect</td>
    <td><button onclick='confByStatus()' class='mainBtn'>View</button></td>
    <td class='explain'>Search your Item IDs by Status</td>
</tr>
</table>";

?>