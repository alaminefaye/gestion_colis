@extends('layouts.app')

@section('title', 'Chat - Gestion des Colis')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Chat & Support Client</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="javascript: void(0)">Communication</a></li>
          <li class="breadcrumb-item" aria-current="page">Chat</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="row g-0">
        <!-- Chat Sidebar -->
        <div class="col-md-4 col-xl-3">
          <div class="card-header border-bottom-0">
            <div class="d-flex align-items-center justify-content-between">
              <h5 class="mb-0">Conversations</h5>
              <div class="dropdown">
                <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="ti ti-dots-vertical f-18"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                  <a class="dropdown-item" href="#">Nouvelle conversation</a>
                  <a class="dropdown-item" href="#">Marquer tout comme lu</a>
                  <a class="dropdown-item" href="#">Paramètres</a>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="search-bar p-3 border-bottom">
              <div class="position-relative">
                <input type="text" class="form-control" placeholder="Rechercher une conversation...">
                <i class="ti ti-search position-absolute top-50 end-0 translate-middle-y me-3"></i>
              </div>
            </div>
            <div class="chat-user-list" style="height: 400px; overflow-y: auto;">
              <!-- Conversation Active -->
              <div class="chat-user-item active">
                <div class="d-flex align-items-center p-3">
                  <div class="flex-shrink-0 position-relative">
                    <div class="avtar avtar-s">
                      <img src="{{ asset('assets/images/user/avatar-1.jpg') }}" alt="user" class="img-radius">
                    </div>
                    <span class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <div class="d-flex align-items-center justify-content-between">
                      <h6 class="mb-0">Jean Dupont</h6>
                      <small class="text-muted">10:30</small>
                    </div>
                    <p class="text-muted mb-0 text-truncate">Bonjour, où en est mon colis COL-2024-001 ?</p>
                  </div>
                  <div class="flex-shrink-0">
                    <span class="badge bg-primary rounded-pill">2</span>
                  </div>
                </div>
              </div>

              <!-- Autres conversations -->
              <div class="chat-user-item">
                <div class="d-flex align-items-center p-3">
                  <div class="flex-shrink-0 position-relative">
                    <div class="avtar avtar-s">
                      <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user" class="img-radius">
                    </div>
                    <span class="position-absolute top-0 end-0 p-1 bg-warning border border-light rounded-circle"></span>
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <div class="d-flex align-items-center justify-content-between">
                      <h6 class="mb-0">Sophie Martin</h6>
                      <small class="text-muted">Hier</small>
                    </div>
                    <p class="text-muted mb-0 text-truncate">Merci pour votre aide !</p>
                  </div>
                </div>
              </div>

              <div class="chat-user-item">
                <div class="d-flex align-items-center p-3">
                  <div class="flex-shrink-0 position-relative">
                    <div class="avtar avtar-s">
                      <img src="{{ asset('assets/images/user/avatar-3.jpg') }}" alt="user" class="img-radius">
                    </div>
                    <span class="position-absolute top-0 end-0 p-1 bg-secondary border border-light rounded-circle"></span>
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <div class="d-flex align-items-center justify-content-between">
                      <h6 class="mb-0">Pierre Bernard</h6>
                      <small class="text-muted">2j</small>
                    </div>
                    <p class="text-muted mb-0 text-truncate">J'ai un problème avec ma livraison</p>
                  </div>
                  <div class="flex-shrink-0">
                    <span class="badge bg-danger rounded-pill">1</span>
                  </div>
                </div>
              </div>

              <div class="chat-user-item">
                <div class="d-flex align-items-center p-3">
                  <div class="flex-shrink-0 position-relative">
                    <div class="avtar avtar-s">
                      <img src="{{ asset('assets/images/user/avatar-4.jpg') }}" alt="user" class="img-radius">
                    </div>
                    <span class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <div class="d-flex align-items-center justify-content-between">
                      <h6 class="mb-0">Claire Moreau</h6>
                      <small class="text-muted">3j</small>
                    </div>
                    <p class="text-muted mb-0 text-truncate">Parfait, livraison reçue</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Chat Area -->
        <div class="col-md-8 col-xl-9">
          <!-- Chat Header -->
          <div class="card-header">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0">
                <div class="avtar avtar-s">
                  <img src="{{ asset('assets/images/user/avatar-1.jpg') }}" alt="user" class="img-radius">
                </div>
              </div>
              <div class="flex-grow-1 ms-3">
                <h6 class="mb-0">Jean Dupont</h6>
                <p class="text-muted mb-0">En ligne</p>
              </div>
              <div class="flex-shrink-0">
                <div class="dropdown">
                  <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ti ti-dots-vertical f-18"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="#">Voir profil client</a>
                    <a class="dropdown-item" href="#">Historique des colis</a>
                    <a class="dropdown-item" href="#">Bloquer</a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Chat Messages -->
          <div class="card-body p-0">
            <div class="chat-messages" style="height: 400px; overflow-y: auto; padding: 20px;">
              <!-- Message reçu -->
              <div class="d-flex mb-3">
                <div class="flex-shrink-0">
                  <div class="avtar avtar-s">
                    <img src="{{ asset('assets/images/user/avatar-1.jpg') }}" alt="user" class="img-radius">
                  </div>
                </div>
                <div class="flex-grow-1 ms-3">
                  <div class="bg-light rounded p-3 mb-1">
                    <p class="mb-0">Bonjour, j'aimerais connaître l'état de mon colis COL-2024-001. Il devait arriver hier mais je n'ai rien reçu.</p>
                  </div>
                  <small class="text-muted">10:25</small>
                </div>
              </div>

              <!-- Message envoyé -->
              <div class="d-flex mb-3 justify-content-end">
                <div class="flex-grow-1 me-3 text-end">
                  <div class="bg-primary text-white rounded p-3 mb-1">
                    <p class="mb-0">Bonjour Jean, je vérifie immédiatement l'état de votre colis.</p>
                  </div>
                  <small class="text-muted">10:26</small>
                </div>
                <div class="flex-shrink-0">
                  <div class="avtar avtar-s">
                    <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user" class="img-radius">
                  </div>
                </div>
              </div>

              <!-- Message avec informations colis -->
              <div class="d-flex mb-3 justify-content-end">
                <div class="flex-grow-1 me-3 text-end">
                  <div class="bg-primary text-white rounded p-3 mb-1">
                    <p class="mb-2">Votre colis est actuellement en transit. Il a quitté notre centre de tri de Lyon ce matin à 8h30.</p>
                    <div class="bg-white bg-opacity-25 rounded p-2">
                      <small><strong>COL-2024-001</strong><br>
                      Statut: En transit<br>
                      Livraison prévue: Aujourd'hui avant 18h</small>
                    </div>
                  </div>
                  <small class="text-muted">10:28</small>
                </div>
                <div class="flex-shrink-0">
                  <div class="avtar avtar-s">
                    <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user" class="img-radius">
                  </div>
                </div>
              </div>

              <!-- Message reçu -->
              <div class="d-flex mb-3">
                <div class="flex-shrink-0">
                  <div class="avtar avtar-s">
                    <img src="{{ asset('assets/images/user/avatar-1.jpg') }}" alt="user" class="img-radius">
                  </div>
                </div>
                <div class="flex-grow-1 ms-3">
                  <div class="bg-light rounded p-3 mb-1">
                    <p class="mb-0">Parfait ! Merci beaucoup pour ces informations. Je vais surveiller.</p>
                  </div>
                  <small class="text-muted">10:30</small>
                </div>
              </div>

              <!-- Notification système -->
              <div class="text-center my-3">
                <span class="badge bg-light text-dark">Jean Dupont est en train d'écrire...</span>
              </div>
            </div>
          </div>

          <!-- Chat Input -->
          <div class="card-footer">
            <div class="row g-2 align-items-center">
              <div class="col">
                <div class="position-relative">
                  <input type="text" class="form-control" id="messageInput" placeholder="Tapez votre message..." style="padding-right: 100px;">
                  <div class="position-absolute top-50 end-0 translate-middle-y me-2">
                    <button type="button" class="btn btn-link btn-sm p-1" data-bs-toggle="tooltip" title="Emoji">
                      <i class="ti ti-mood-smile f-18"></i>
                    </button>
                    <button type="button" class="btn btn-link btn-sm p-1" data-bs-toggle="tooltip" title="Joindre fichier">
                      <i class="ti ti-paperclip f-18"></i>
                    </button>
                  </div>
                </div>
              </div>
              <div class="col-auto">
                <button type="button" class="btn btn-primary" id="sendMessage">
                  <i class="ti ti-send"></i>
                </button>
              </div>
            </div>
            <!-- Quick Responses -->
            <div class="mt-2">
              <div class="d-flex flex-wrap gap-1">
                <button type="button" class="btn btn-outline-secondary btn-sm quick-response">Merci pour votre message</button>
                <button type="button" class="btn btn-outline-secondary btn-sm quick-response">Je vérifie votre colis</button>
                <button type="button" class="btn btn-outline-secondary btn-sm quick-response">Votre colis est en route</button>
                <button type="button" class="btn btn-outline-secondary btn-sm quick-response">Problème résolu</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
.chat-user-item {
  cursor: pointer;
  transition: all 0.3s ease;
}
.chat-user-item:hover {
  background-color: #f8f9fa;
}
.chat-user-item.active {
  background-color: #e3f2fd;
  border-left: 3px solid #4099ff;
}
.chat-messages {
  background: #f8f9fa;
}
.quick-response {
  font-size: 0.8rem;
}
.search-bar input {
  padding-right: 40px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Initialize tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Chat user selection
  document.querySelectorAll('.chat-user-item').forEach(item => {
    item.addEventListener('click', function() {
      document.querySelectorAll('.chat-user-item').forEach(i => i.classList.remove('active'));
      this.classList.add('active');
    });
  });

  // Send message
  function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    
    if (message) {
      const chatMessages = document.querySelector('.chat-messages');
      const messageHtml = `
        <div class="d-flex mb-3 justify-content-end">
          <div class="flex-grow-1 me-3 text-end">
            <div class="bg-primary text-white rounded p-3 mb-1">
              <p class="mb-0">${message}</p>
            </div>
            <small class="text-muted">${new Date().toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})}</small>
          </div>
          <div class="flex-shrink-0">
            <div class="avtar avtar-s">
              <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user" class="img-radius">
            </div>
          </div>
        </div>
      `;
      
      chatMessages.insertAdjacentHTML('beforeend', messageHtml);
      messageInput.value = '';
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }
  }

  // Send message on button click
  document.getElementById('sendMessage').addEventListener('click', sendMessage);

  // Send message on Enter key
  document.getElementById('messageInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      sendMessage();
    }
  });

  // Quick responses
  document.querySelectorAll('.quick-response').forEach(btn => {
    btn.addEventListener('click', function() {
      document.getElementById('messageInput').value = this.textContent;
    });
  });

  // Auto scroll to bottom
  const chatMessages = document.querySelector('.chat-messages');
  chatMessages.scrollTop = chatMessages.scrollHeight;

  // Simulate typing indicator
  function showTypingIndicator() {
    const indicator = document.querySelector('.badge');
    if (indicator) {
      indicator.style.display = 'inline-block';
      setTimeout(() => {
        indicator.style.display = 'none';
      }, 3000);
    }
  }

  // Search conversations
  document.querySelector('.search-bar input').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const conversations = document.querySelectorAll('.chat-user-item');
    
    conversations.forEach(conv => {
      const text = conv.textContent.toLowerCase();
      conv.style.display = text.includes(searchTerm) ? 'block' : 'none';
    });
  });
});
</script>
@endpush
