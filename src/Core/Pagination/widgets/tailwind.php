<div class="rounded-lg flex items-center flex-wrap">
  <nav aria-label="Page navigation" class="bg-white rounded-lg">
    <ul class="inline-flex">
      <a href=<?= $pagination->createUrl($pagination->currentPage - 1) ?> <li>
        <button class="px-4 py-2 border border-r-0 rounded-l-lg">Prev</button>
        </li>
      </a>
      <?php foreach (range(1, $pagination->pageCount) as $page) {
          $bgColor = "bg-white";
          if ($pagination->currentPage == $page) {
              $bgColor = "bg-gray-300";
          }
          $linkToPage =  $pagination->createUrl($page);
          echo ("<a href=$linkToPage
        <li>
          <button class='px-4 py-2 $bgColor border border-r-0 '>$page</button>
        </li></a>");
      } ?>
      <a href=<?= $pagination->createUrl($pagination->currentPage + 1) ?> <li>
        <button class="px-4 py-2 bg-white border rounded-r-lg">Next</button>
        </li>
      </a>
    </ul>
  </nav>
</div>