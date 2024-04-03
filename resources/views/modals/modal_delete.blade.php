<div class="modal fade right modal-del" id="sideModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-side modal-sm" role="document">
    <div class="modal-content">
    <form action="#" name="deleteChoose" method="POST">
      @csrf
      <div class="modal-header">
        <h4 class="modal-title w-100" id="myModalLabel">{{__('Xóa')}}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">{{__('Bạn có muốn xóa mục này không?')}}</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Trở về')}}</button>
        <button type="submit" class="btn btn-primary">{{__('OK')}}</button>
      </div>
    </div>
    </form>
  </div>
</div>