# 🏗️ Laravel Enterprise: Modular Hexagonal DDD Architecture

This repository serves as the official architectural standard and reference implementation for our enterprise applications. We combine **Hexagonal Architecture** (Ports & Adapters) with **Domain-Driven Design (DDD)** to build a system that is technologically agnostic, highly testable, and maintainable at scale.

---

## **1. Core Philosophy**

The primary goal of this architecture is to decouple the **Business Logic (The Domain)** from the **Technical Implementation (The Infrastructure)**.

* **Domain-Centricity:** Business rules must remain "pure" and have zero dependencies on Laravel, Databases, or Third-party APIs.
* **Modularity (Bounded Context):** The application is divided into independent modules (e.g., Sales, Inventory, Finance). Each module is a self-contained unit.
* **Dependency Inversion:** The Domain defines **Ports** (Interfaces), and the Infrastructure provides **Adapters** (Implementations).

---

## **2. Structure**

### **2.1. Directory Structure**

Every module in `app/Modules/{ModuleName}` follows this strict hierarchy:

```yaml
app/
├── Modules/
│   └── {ModuleName}/
│       ├── Application/                    # THE ORCHESTRATOR
│       │   ├── DTO/                        # Data Transfer Objects
│       │   ├── Providers/                  # Module providers
│       │   └── UseCases/                   # Multi-step business process orchestration
│       ├── Domain/                         # THE BRAIN ( Pure PHP )
│       │   ├── Entities/                   # Core business rules and logic
│       │   ├── Events/                     # Module events ( for communicating with other module ) ( Optional )
│       │   ├── Exceptions/                 # Custom exceptions ( Optional )
│       │   ├── Ports/                      # Interfaces ( Contracts for DB/API )
│       │   └── ValueObjects/               # Immutable objects ( e.g., Price, Email ) ( Optional )
│       ├── Infrastructure/                 # THE TOOLS ( Implementation )
│       │   ├── Config/                     # Module configuration ( Optional )
│       │   ├── ExternalServices/           # Service for communicate with other module ( Optional )
│       │   ├── Persistence/                # The implementation from framework it self
│       │   │   ├── Eloquent/               # Database implementation from framework ( can be change depends of data usage )
│       │   │   │   ├── Factories/          # Localized Model Factories
│       │   │   │   ├── Models/             # Eloquent Models
│       │   │   │   └── Repositories/       # Adapters implementing Domain Ports
│       │   │   └── Migrations/             # Localized Database Migrations
│       │   └── ExternalAPI/                # 3rd Party Integrations ( e.g., Stripe ) ( Optional )
│       ├── Tests/                          # Module tests
│       │   ├── Browser/                    # Test if using Laravel Dusk ( Optional )
│       │   ├── Feature/                    # Test for one cycle process ( r.g. UseCase, API, etc. )
│       │   └── Unit/                       # Test for simple process ( e.g., Domain, Infrastructure, etc. )
│       └── UI/                             # The gateway for users to interact with the application.
│           ├── Controllers/                # To handle all request from user
│           ├── Filament/                   # UI Admin Resources ( Optional )
│           ├── Middleware/                 # To check all request before controller is executed
│           ├── Requests/                   # To handle the data sent by user
│           └── Routes/                     # To define how the application responds to incoming HTTP requests
└── Shared/                                 # Shared logic that should be usable by all modules
    └── {ModuleName}/
        └── ...                             # ( the Directory structure is similar with the Sub Module )
```

### **2.2. The Dependency Rule**

Code dependencies must strictly point inwards.

* **Domain Layer** must have **zero** dependencies on Laravel or any external libraries.
* **Application Layer** depends on the **Domain Layer**.
* **Infrastructure & UI Layers** depend on the **Application and Domain Layers**.
* **Ports (Interfaces)** reside in the Domain, but their **Adapters (Implementations)** reside in Infrastructure.

### **2.3. Naming & Coding Standards**

#### **2.3.1. Naming Conventions**

|**Component**|**Convention**|**Example**|
|---|---|---|
|**UseCase**|`[Feature]UseCase`|`PlaceOrderUseCase.php`|
|**DTO**|`[Name]RequestDTO`|`OrderRequestDTO.php`|
|**Port (Interface)**|`[Name]RepositoryInterface`|`OrderRepositoryInterface.php`|
|**Adapter (Implementation)**|`[Name]Repository`|`OrderRepository.php`|
|**Entity**|`[Name]Entity`|`OrderEntity.php`|
|**Eloquent**|`[Name]Model`|`OrderModel.php`|

#### **2.3.1. Shared Kernel**

Components used by multiple modules are stored in `app/Shared/`.

Example:

* `app/Shared/Domain/Entities/AggregateRoot.php`
* `app/Shared/UI/Responses/ApiResponse.php`

### **2.4. Communication Flow (The Golden Flow)**

All communication flows in sequence as follows:

> 1. **UI Controller** receives an HTTP Request.
> 2. **UI Request** validates the input format.
> 3. **UI Controller** maps input to a **DTO**.
> 4. **Application UseCase** is called in **UI Controller** with the **DTO**.
> 5. **Application UseCase** interacts with **Domain Entities** for logic and **Domain Ports** for data persistence.
> 6. **Domain Port** is called **Infrastructure Persistence** to call Repository Implementation.
> 7. **Infrastructure Persistence** executes the actual Database/API operations.
> 8. **Application UseCase** is returning back the result to **UI Controller**
> 9. **UI Controller** returns a standardized **API Response**.

---

## **3. Automation & Tooling**

To ensure architectural consistency, always use the custom Artisan commands:

### **3.1. Create a New Module**
```bash
php artisan make:module {ModuleName}
```
*Generates the entire folder structure and initializes a new module.*

### **3.2. Create a Module Model**
```bash
php artisan make:module-model {ModuleName} {ModelName}
```
*Creates Hexagonal DDD model file inside the specific module.*

---

## **4. Technical Implementation Standards**

###  **4.1. Class Member Ordering**

To maintain readability and a clean API, all class members (properties and methods) must be ordered by visibility:

* **Public**: Primary interface for external use.
* **Protected**: Internal logic shared with subclasses.
* **Private**: Strict internal logic for the specific class.

>*Order Flow: **Traits** > **Constants** > **Properties** > **Constructor** > **Methods**.*

### **4.2. The Model Pattern**

Models must not contain business logic. To prevent table identity bugs during instantiation, anything should be settled in this model is set before the parent constructor is called.

All the procedural belongs to Model Framework is write here (cast, relationship, etc).

Then after that it is highly recommended to use setter and getter methods.

```php
// Custructor
public function __construct(array $attributes = [])
{
    // - Configure identity table first ( use snack_case with plural name )
    $this->setTable('[table_names]');

    // - Configure what table column should be mutated
    $this->fillable([]);

    // - Configure the factory class from this model
    $this->modelFactory = [class_name]Factory::class;

    // - Last, call parent constructor
    parent::__construct($attributes);
}

// any procedural codes belongs to Model Framework is write here...

// Getters and Setters must be written last.
```

#### **4.2.1. Eloquent Encapsulation Standard (Getters & Setters)**

To prevent direct coupling between the Database schema and the Application layer, we strictly forbid accessing Eloquent attributes directly via magic properties. Every model must explicitly define **Getters** and **Setters**.

* **Implementation Pattern**

    Developers must hide the raw attributes by providing explicit methods. This ensures that if a database column is renamed (e.g., from `full_name` to `name`), we only need to update the model's methods.

    ```php
    namespace App\Modules\User\Infrastructure\Persistence\Eloquent\Models;

    use Illuminate\Database\Eloquent\Model;

    class UserModel extends Model
    {
        /**
         * GETTER: Always use getX() to retrieve data.
         */
        public function getName(): ?string
        {
            return $this->__get('name');
        }

        /**
         * SETTER: Always use setX() to modify data.
         * This allows us to intercept and validate or format data.
         */
        public function setName(string $name): static
        {
            $this->__set('name', trim($name));

            return $this;
        }
    }
    ```

* **Usage Comparison**

    |**Practice**|**Bad (Magic Properties)**|**Good (Encapsulated)**|
    |---|---|---|
    |**Reading**|`$user->name;`|`$user->getName();`|
    |**Writing**|`$user->name = 'John';`|`$user->setName('John');`|

* **Why This is Mandatory**

    * **Schema Agnostic**: If the table column `is_active` changes to status (integer), we simply update `isActive()` to return `$this->status === 1`. The rest of the app remains untouched.
    * **Auto-Complete Support**: IDEs (like VS Code or PHPStorm) will suggest `getName()` via IntelliSense, whereas magic properties often require extra plugins.
    * **Consistency with Domain Entities**: This matches the style of our Domain Entities, reducing cognitive load when switching between layers.

* **Rules of Thumb**

    > ⚠️ **STRICT RULE**: Direct access to `$model->attribute` is deprecated. Always define and use `getX()` and `setX()` methods. If you find a model without these methods, you are required to refactor it before use.

### **4.3. The Repository Pattern Standard**

We utilize the Repository Pattern to decouple the Domain from the Infrastructure. This ensures that business logic remains untouched even if the underlying database technology changes.

#### **4.3.1. Core Responsibilities**

* **Abstraction**: The Application layer interacts only with the **Port ( Interface )**.
* **Translation**: The Repository maps **Eloquent Models ( Data )** to **Domain Entities ( Logic )** and vice versa.
* **Encapsulation**: Complex queries (Joins, Query Builders, DB Raw) are strictly forbidden in Use Cases. They must reside within the Repository implementation.

#### **4.3.2. Architecture Implementation Rule**

|**Component**|**Layer**|**Purpose**|
|---|---|---|
|**Interface (Port**)|Domain|Defines the contract ( e.g., `findById`, `save` ).|
|**Implementation**|Infrastructure|Concrete logic using Eloquent or SQL.|
|**Entity Mapping**|Infrastructure|The act of converting Models to Entities and back.|

#### **4.3.3. Robust Repository Implementation Example**

* The Domain Port ( Interface )

    ```php
    namespace App\Modules\Order\Domain\Ports;

    use App\Modules\Order\Domain\Entities\OrderEntity;

    interface OrderRepositoryInterface
    {
        /**
         * Retrieves an Entity from the persistence layer.
         */
        public function findById(int $id): ?OrderEntity;

        /**
         * Persists an Entity state back to the database.
         */
        public function save(OrderEntity $entity): void;
    }
    ```

* The Infrastructure Implementation ( Adapter )

    ```php
    namespace App\Modules\Order\Infrastructure\Persistence\Eloquent\Repositories;

    use App\Modules\Order\Domain\Ports\OrderRepositoryInterface;
    use App\Modules\Order\Domain\Entities\OrderEntity;
    use App\Modules\Order\Infrastructure\Persistence\Eloquent\Models\OrderModel;

    class EloquentOrderRepository implements OrderRepositoryInterface
    {
        public function findById(int $id): ?OrderEntity
        {
            $model = OrderModel::find($id);

            if (! $model) {
                return null;
            }

            // MAPPING: Eloquent Model (Infrastructure) -> Domain Entity (Domain)
            // We use our standardized Model Getters (getId, getStatus, etc.)
            return new OrderEntity(
                id: $model->getId(),
                status: $model->getStatus(),
                amount: $model->getAmount()
            );
        }

        public function save(OrderEntity $entity): void
        {
            // MAPPING: Domain Entity (Domain) -> Eloquent Model (Infrastructure)
            OrderModel::updateOrCreate(
                ['id' => $entity->getId()],
                [
                    'status' => $entity->getStatus(),
                    'amount' => $entity->getAmount(),
                ]
            );
        }
    }
    ```

#### **4.3.4. Why Manual Mapping is Required?**

* **Schema Decoupling**: If we rename a database column ( e.g., `is_paid` to `payment_status` ), we only update the Repository. The Use Case and Domain logic remain perfectly intact.
* **Strict Business Rules**: A Domain Entity can have complex logic that an Eloquent Model cannot. By mapping to an Entity, we guarantee that any object used in the Use Case is a "Business Object," not just a database row.
* **Encapsulation & Security**: It prevents sensitive database attributes ( like `password_hash` or internal metadata ) from accidentally leaking into the UI or API responses, as the Entity only contains business-relevant data.

#### **4.3.5. The Repository Pattern Summary**

Please do refer to `2.4. Communication Flow` for the repository pattern flow usage.

> **UI** ➔ **UseCase** ➔ calls **Port ( Interface )** ➔ triggers **Repository ( Eloquent )** ➔ returns **Domain Entity**.

### **4.4. Advanced Standardization**

#### **4.4.1. Cross-Module Communication Standards**

* To maintain strict isolation between modules, modules must interact using strictly defined patterns. We categorize these into three communication strategies:

    **1. Asynchronous Communication (Domain Events)**
    **Best Practice**: This is the most recommended method. It ensures **Loose Coupling**, meaning the "Source Module" doesn't need to know who is listening or what they do.

    * When to use: For side effects like sending notifications, updating stock, or generating invoices after a primary action is completed.
    * Layer: Domain (Event) ➔ Infrastructure (Listener).

    **Source Module (Sales - Domain Event):**

    ```php
    namespace App\Modules\Sales\Domain\Events;

    /**
     * Defined in the Domain layer as a simple POPO.
     */
    class OrderPaid {
        public function __construct(
            public int $orderId, 
            public int $customerId, 
            public int $amount
        ) {}
    }
    ```

    **Target Module (Inventory - Infrastructure Listener):**

    ```php
    namespace App\Modules\Inventory\Infrastructure\Listeners;

    use App\Modules\Sales\Domain\Events\OrderPaid;

    class ReduceStockOnOrderPaid {
        public function handle(OrderPaid $event): void {
            // Logic to decrease inventory stock based on the order
        }
    }
    ```

    **Register to Module Service Provider:**

    ```php
    namespace App\Modules\Sales\Application\Providers;

    use App\Modules\Inventory\Infrastructure\Listeners\ReduceStockOnOrderPaid;
    use App\Modules\Sales\Domain\Events\OrderPaid;
    use Illuminate\Support\Facades\Event;

    class ServiceProvider extends \Illuminate\Support\ServiceProvider
    {
        public function boot()
        {
            Event::listen(
                OrderPaid::class,
                ReduceStockOnOrderPaid::class
            );
        }
    }
    ```

    **2. Synchronous Communication (Module Ports/Interfaces)**

    **Standard**: Use this when a module needs an immediate response from another module (Request-Response) before proceeding.

    * **When to use**: Data fetching or validation across modules (e.g., "Is this product in stock?" or "Is this coupon valid?").
    * **Layer**: **Domain** (Port) ➔ **Application** (UseCase).

    **Target Module (Inventory - Domain Port):**

    ```php
    namespace App\Modules\Inventory\Domain\Ports;

    interface InventoryModuleInterface {
        public function isStockAvailable(int $productId, int $quantity): bool;
    }
    ```

    **Source Module (Sales - Application UseCase):**

    ```php
    namespace App\Modules\Sales\Application\UseCases;

    use App\Modules\Inventory\Domain\Ports\InventoryModuleInterface;
    use App\Modules\Sales\Domain\Exceptions\DomainException;

    class CreateOrderUseCase {
        public function __construct(
            private InventoryModuleInterface $inventoryService // Dependency Injected
        ) {}

        public function execute($dto): void {
            // Synchronous check to another module
            if (!$this->inventoryService->isStockAvailable($dto->productId, $dto->qty)) {
                throw new DomainException("Insufficient stock.");
            }

            // Proceed with order creation...
        }
    }
    ```

    **3. UI Level Integration (Shared Components)**

    **Standard**: Applied at the outermost layer for presentation purposes. We prioritize **KISS** (Keep It Simple, Stupid) here to avoid unnecessary UseCase complexity for simple displays.

    * **When to use**: Displaying data from another module in a table, dropdown, or dashboard widget.
    * **Layer**: Infrastructure (UI/Filament).

    **Source Module (Sales - Infrastructure Filament Resource):**

    ```php
    namespace App\Modules\Sales\Infrastructure\UI\Filament\Resources;

    use App\Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
    use Filament\Tables\Columns\TextColumn;

    class OrderResource {
        public static function table(Table $table): Table {
            return $table->columns([
                TextColumn::make('order_number'),

                // Pragmatic UI Integration: Fetching User data directly from User Module Model
                TextColumn::make('user_id')
                    ->label('Customer')
                    ->formatStateUsing(fn ($state) => UserModel::find($state)?->getName()),
            ]);
        }
    }
    ```

    **Summary of Communication Strategies**

    |**Strategy**|**Coupling**|**Reliability**|**Best For**|
    |---|---|---|---|
    |**Domain Events**|**Ultra-Low**|High (if queued)|Side-effects & Decoupling|
    |**Module Port**|**Medium**|Dependent|Real-time Validation & Data|
    |**UI Integration**|**High**|N/A|Table Displays & Labels|

    ---

    **The "Red Line" Rules (Mandatory)**

    * **No Direct Repository Access**: A UseCase in Module A is **STRICTLY FORBIDDEN** from calling a Repository in Module B. Use the `ModuleInterface` ( Port ) instead.
    * **No Cross-Module SQL Joins**: Do not perform SQL joins across tables belonging to different modules. Fetch IDs first, then request data through the Port.
    * **Encapsulation First**: When fetching data from another module, always use the target model's **Getters** ( `getName()`, `getId()` ). Never access properties directly.

#### **4.4.2. Transaction Management**

All Use Cases that involve multiple persistence calls must be wrapped in a database transaction to ensure data atomicity.

```php
// Application Layer Example
public function execute(Request $dto): void {
    DB::transaction(function () use ($dto) {
        $order = $this->repository->findById($dto->id);
        $order->process();
        $this->repository->save($order);
        
        // This ensures both succeed or both fail
        $this->accountingRepository->log($order);
    });
}
```

### **4.5. UI & Business Action Logic**

To prevent business logic leakage into the UI layer (Controllers, Commands, or APIs), we follow these two primary rules:

1. **Standard CRUD (The Passive Path):** Operations that only involve basic data management (e.g., updating a category name, listing units, simple deletions) may interact directly with **Eloquent Models**. This avoids unnecessary boilerplate for non-business operations.
   
2. **Business Actions (The Active Path):** Any operation that represents a meaningful business event or state transition **MUST** be encapsulated within an **Application Use Case**. 
    * **Examples:** "Authorize Refund", "Approve Order", "Check-in Stock", "Finalize Payroll."
    * **Why?:** This ensures that side effects (sending emails, logging audits, updating related aggregates) are handled consistently regardless of which UI triggers them.

---

## **5. Quick Reference: "Where Does This Code Go?"**

|**Scenario / Task**|**Directory Path**|**Layer**|
|---|---|---|
|**Database Schema** (Migrations)|`Infrastructure/Persistence/Eloquent/Migrations/`|Infrastructure|
|**Pure Business Rules** (Entities)|`Domain/Entities/`|Domain|
|**Database Contracts** (Interfaces)|`Domain/Ports/`|Domain|
|**Process Orchestration** (UseCases)|`Application/UseCases/`|Application|
|**Data Persistence** (Repositories)|`Infrastructure/Persistence/Eloquent/Repositories/`|Infrastructure|
|**External Integration** (APIs)|`Infrastructure/ExternalAPIs/`|Infrastructure|

---

## **6. Core Coding Principles**

To maintain a high-standard codebase, every developer must adhere to these four foundational principles:

### **6.1. SOLID Principles**

We prioritize Interface Segregation and Dependency Inversion to keep our Hexagonal layers decoupled.

* **Single Responsibility**: A class should have one, and only one, reason to change. ( e.g., A Use Case only orchestrates, it doesn't calculate taxes ).
* **Open/Closed**: Software entities should be open for extension, but closed for modification.
* **Liskov Substitution**: Subtypes must be substitutable for their base types.
* **Interface Segregation**: Don't force a class to implement interfaces it doesn't use.
* **Dependency Inversion**: Depend on abstractions ( Ports ), not concretions ( Eloquent/Adapters ).

### **6.2. Clean Code**

Code is read much more often than it is written.

* **Meaningful Names**: Use intent-revealing names for variables and methods ( e.g., `isEligibleForDiscount()` instead of `check()` ).
* **Small Methods**: Methods should do one thing and be as small as possible.
* **Function Arguments**: Limit the number of arguments ( prefer DTOs for complex data ).

### **6.3. DRY (Don't Repeat Yourself)**

Every piece of knowledge must have a single, unambiguous representation within the system.

* **Domain Logic**: Must reside **ONLY** in the Domain layer. Never duplicate logic in both a Controller and a Background Job.
* **Shared Infrastructure**: Use the `app/Shared` directory for code used by multiple modules ( e.g., Base Traits, Money formatters ).

### **6.4. KISS (Keep It Simple, Stupid)**

Avoid over-engineering.

* If a feature can be implemented using a standard Laravel feature without breaking the architecture ( like Simple CRUD in Filament ), do it.
* Do not create abstractions ( Interfaces/Ports ) unless there is a clear need for decoupling or multiple implementations.

### **6.5. Summary Table of Architecture Responsibilities**

|**Principle**|**Primary Focus**|**Where it is most visible?**|
|---|---|---|
|**SOLID**|Flexibility|Interface & Class Design|
|**Clean Code**|Readability|Inside Methods & Classes|
|**DRY**|Consistency|Logic Placement ( Domain Layer )|
|**KISS**|Maintainability|Architectural Decisions|

---

## **7. External Integration Strategy**
### **7.1. Filament**
Since Filament is highly coupled with Eloquent, we use a pragmatic approach to keep our architecture clean without fighting the framework.

#### **7.1.1. Resource Forms & Tables**
* **Data Display:** Use Eloquent Models directly in `table()` and `form()` methods.
* **Simple Mutations:** Standard `CreateRecord` and `EditRecord` pages can use Eloquent as long as no complex business rules are triggered.

#### **7.1.2. Custom Actions & State Transitions**
When implementing `Actions` (Header Actions, Table Actions, or Bulk Actions) that trigger business logic:
* **Decoupling:** Do not write business logic inside the `action(fn() => ...)` closure.
* **Delegation:** Use the closure only to collect input, then instantiate and execute the relevant **Application Use Case**.

#### **7.1.3. Error Handling & Notifications**
* **Domain Exceptions**: Any DomainException thrown by the Use Case must be caught within the Filament Action.
* **User Feedback**: Use Filament’s Notification class to display clear, friendly error messages or success confirmations.

#### **7.1.4. Placement**
All Filament-related files (Resources, Pages, Widgets) must be stored within:
`app/Modules/{ModuleName}/UI/Filament/`

#### **7.1.5. Filament vs. UseCase**

To make sure what to do and what not to do when using UseCase or directly in Filament, please see some of the example cases below:

|**Activity**|**Direct Eloquent/Filament?**|**Use Case Required?**|
|---|---|---|
|Editing a Product Name|**Yes** (Standard CRUD)|No|
|Changing a User's Role|**No** (Security/Business Event)|Yes|
|Generating an Invoice|**No** (Complex Logic)|Yes|
|Archiving a Record|**Yes** (Soft Delete)|No|
|Processing a Refund|**No** (Financial Impact)|Yes|

---

