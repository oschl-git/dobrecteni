// let books = array of books from the database
// let phpSelf = refers to this page

// Node references:
const bookTable = document.querySelector('#book-table');


const containers = {
	details: document.querySelector('#details-container'),
	edit: document.querySelector('#edit-container'),
	delete: document.querySelector('#delete-container'),
	add: document.querySelector('#add-container'),
}

const detailsFields = {
	name: document.querySelector('#details-name'), 
	author: document.querySelector('#details-author'), 
	genre: document.querySelector('#details-genre'), 
	read: document.querySelector('#details-read'), 
	rating: document.querySelector('#details-rating'), 
	isbn: document.querySelector('#details-isbn'), 
	datePublished: document.querySelector('#details-date'), 
	pages: document.querySelector('#details-pages'), 
	notes: document.querySelector('#details-notes'),
	cover: document.querySelector('#details-cover'),
};

const deleteFields = {
	name: document.querySelector('#delete-name'), 
	id: document.querySelector('#delete-book-id'),
};

// Variables:
let currentBook;

showBoooksFromArray(books);

function showBoooksFromArray(array) {
	let index = 0;
	for (const book of array) {
		showBook(book, index);
		index++;
	}
}

function showBook(book, index) {
	bookTable.innerHTML += `
		<tr>
			<td>` + String(book['name']) + `</td>
			<td>` + String(book['author']) + `</td>
			<td>` + String(book['genre']) + `</td>
			<td>` + String(book['rating']) + `</td>
			<td>` + String(book['read']) + `</td>
			<td>
				<button type="button" data-book-index="` + index + `" onclick="showDetails(this)">Details</button>
				<button type="button" data-book-index="` + index + `" onclick="editBook(this)">Edit</button>
				<button type="button" data-book-index="` + index + `" onclick="deleteBook(this)">Delete</button>
			</td>
		</tr>`;
}

function showDetails(clickedElement = null) {
	if (clickedElement != null) currentBook = books[clickedElement.getAttribute('data-book-index')];

	detailsFields['name'].innerHTML = currentBook['name'];
	detailsFields['author'].innerHTML = currentBook['author'];
	detailsFields['genre'].innerHTML = currentBook['genre'];
	detailsFields['read'].innerHTML = currentBook['read'];
	detailsFields['rating'].innerHTML = currentBook['rating'];
	detailsFields['isbn'].innerHTML = currentBook['isbn'];
	detailsFields['datePublished'].innerHTML = currentBook['date_published'];
	detailsFields['pages'].innerHTML = currentBook['pages'];
	detailsFields['notes'].innerHTML = currentBook['notes'];

	detailsFields['cover'].setAttribute('src', 'https://covers.openlibrary.org/b/isbn/' + currentBook['isbn'] + '-M.jpg');

	showContainer('details');
}

function editBook(clickedElement) {
	showContainer('edit');
}

function deleteBook(clickedElement) {
	if (clickedElement != null) currentBook = books[clickedElement.getAttribute('data-book-index')];

	deleteFields['name'].innerHTML = currentBook['name'];
	deleteFields['id'].setAttribute('value', currentBook['id']);

	showContainer('delete');
}

function addBook(clickedElement) {
	showContainer('add');
}

function showContainer(name) {
	hideAllContainers();
	containers[name].style.display = 'block';
}

function hideAllContainers() {
	for (const container of Object.values(containers)) {
		container.style.display = 'none';
	}
}