<div class="d-flex justify-content-center align-items-start flex-fill vh-100">
  
  <div class="table-responsive w-75 mt-5"> 
    <h1 class="display-4 fw-bold mb-1"><?= esc($title) ?></h1>
    <table class="table table-dark table-hover text-center mb-0">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Title</th>
          <th scope="col">Image</th>
          <th scope="col">Author</th>
          <th scope="col">Year</th>
          <th scope="col">Create Time</th>
          <th scope="col">Update Time</th>
          <th scope="col">Slug</th>
          <th scope="col">Price</th>
        </tr>
      </thead>
      <tbody id="book-table-body">
        <!-- Book rows will be dynamically inserted here -->
      </tbody>
    </table>
  </div>

</div>