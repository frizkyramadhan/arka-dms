{{-- <a href="{{ url('trackings?search=' . $model->receipt_full_no) }}" class="btn btn-icon btn-light" title="Track"><i class="fas fa-search-location"></i></a>
<a href="{{ url('transmittals/' . $model->id) }}" class="btn btn-icon btn-primary" title="Detail"><i class="fas fa-info-circle"></i></a>
<a href="{{ url('transmittals/' . $model->id . '/edit') }}" title="Edit" class="btn btn-icon btn-warning"><i class="far fa-edit"></i></a>
<form action="{{ url('transmittals/' . $model->id) }}" method="post" onsubmit="return confirm('Are you sure want to delete this data?')" class="d-inline">
  @method('delete')
  @csrf
  <button class="btn btn-icon btn-danger" title="Delete"><i class="fas fa-times"></i></button>
</form> --}}
<div>
  <span id="receipt-no-{{ $model->id }}">{{$model->receipt_full_no}}</span>
  <a href="#" class="copy-button" data-target="receipt-no-{{ $model->id }}">
    <i class="far fa-copy"></i>
  </a>
</div>
<div class="table-links">
  <a href="{{ url('transmittals/' . $model->id) }}">View</a>
  <div class="bullet"></div>
  <a href="{{ url('transmittals/' . $model->id . '/edit') }}">Edit</a>
  <div class="bullet"></div>
  <form action="{{ url('transmittals/' . $model->id) }}" method="post" onsubmit="return confirm('Are you sure want to delete this data?')" class="d-inline">
    @method('delete')
    @csrf
    <button class="btn btn-sm btn-danger btn-link text-white">Delete</button>
  </form>
</div>
<script>
  $(document).ready(function() {
    $('.copy-button').click(function() {
      var targetId = $(this).data('target');
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($('#' + targetId).text()).select();
      document.execCommand("copy");
      $temp.remove();

      $(this).addClass('copied');
      $(this).text('Copied');
      setTimeout(() => {
        $(this).removeClass('copied');
        $(this).html('<i class="far fa-copy"></i>');
      }, 2000);
    });
  });

</script>
