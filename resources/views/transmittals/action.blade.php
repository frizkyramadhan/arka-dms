<a href="" class="btn btn-icon btn-info" title="Send" data-toggle="modal"
  data-target="#exampleModal-{{ $model->id }}"><i class="fas fa-paper-plane"></i></a>
<a href="{{ url('transmittals/' . $model->id) }}" class="btn btn-icon btn-primary" title="Detail"><i
    class="fas fa-info-circle"></i></a>
<a href="{{ url('transmittals/' . $model->id . '/edit') }}" title="Edit" class="btn btn-icon btn-warning"><i
    class="far fa-edit"></i></a>
<form action="{{ url('transmittals/' . $model->id) }}" method="post"
  onsubmit="return confirm('Are you sure want to delete this data?')" class="d-inline">
  @method('delete')
  @csrf
  <button class="btn btn-icon btn-danger" title="Delete"><i class="fas fa-times"></i></button>
</form>
