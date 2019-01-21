{section name="message" loop=1 show=$info.message}
    NOTICE: {$info.message}
{/section}
<form enctype="multipart/form-data" action="{$info.current_page}" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="300000" />
Use this form to load new TDA information into the database.

<p>
TDA Year:
  <select name="year" size="1">
    <option value="2004">2004</option>
    <option value="2005">2005</option>
    <option value="2006">2006</option>
  </select>

<p>
CSV File:
  <input type="file" name="file">

<p>
<input type="submit" name="submit" value="Load File" class="button">
</form>