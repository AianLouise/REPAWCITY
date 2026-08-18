import PetForm from './PetForm'

export default function AdminAddPet() {
  return (
    <div className="max-w-3xl mx-auto">
      <PetForm mode="create" />
    </div>
  )
}
